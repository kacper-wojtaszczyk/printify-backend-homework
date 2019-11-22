<?php
declare(strict_types=1);


namespace KacperWojtaszczyk\PrintifyBackendHomework\Application\Controller\Rest;


use KacperWojtaszczyk\PrintifyBackendHomework\Application\Form\Model\ProductFormModel;
use KacperWojtaszczyk\PrintifyBackendHomework\Application\Form\ProductForm;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Price;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Color;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Exception\ProductAlreadyExistsException;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductId;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductType;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Size;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductRepositoryInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Product as PersistentProduct;
use KacperWojtaszczyk\PrintifyBackendHomework\Application\Helper\ExceptionHelper;

class Product
{
    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var string
     */
    private $currency;

    public function __construct(
        FormFactoryInterface $formFactory,
        ProductRepositoryInterface $productRepository,
        string $currency
    ) {
        $this->formFactory = $formFactory;
        $this->productRepository = $productRepository;
        $this->currency = $currency;
    }

    /**
     * @Route("/product", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $model = new ProductFormModel();
        $form = $this->formFactory->create(ProductForm::class, $model);
        try{
            $form->submit($request->request->all());
            if ($form->isValid()) {
                $data = $form->getData();

                $productType = new ProductType($data->productType);
                $color = new Color($data->color);
                $size = new Size($data->size);

                if($this->productRepository->findOneByTypeColorSize($productType, $color, $size))
                {
                    throw ProductAlreadyExistsException::withData($productType, $color, $size);
                }

                $price = new Price($data->price, $this->currency);
                $productId = ProductId::fromProductTypeColorSize($productType, $color, $size);

                $product = PersistentProduct::withParameters($productId, $price, $productType, $color, $size);

                $this->productRepository->save($product);

                return JsonResponse::create(
                    [
                        'id' => (string) $product->getId(),
                        'productType' => (string) $product->getProductType(),
                        'color' => (string) $product->getColor(),
                        'size' => (string) $product->getSize()
                    ],
                    Response::HTTP_CREATED);
            }
        } catch(\Throwable $e)
        {
            return JsonResponse::create(
                [
                    'code' => ExceptionHelper::mapToHttpCode($e),
                    'message' =>$e->getMessage()
                ], ExceptionHelper::mapToHttpCode($e)
            );
        }
    }
}