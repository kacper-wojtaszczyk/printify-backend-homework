<?php
declare(strict_types=1);


namespace KacperWojtaszczyk\PrintifyBackendHomework\Application\Controller\Rest;


use http\Exception\InvalidArgumentException;
use KacperWojtaszczyk\PrintifyBackendHomework\Application\Form\Model\ProductFormModel;
use KacperWojtaszczyk\PrintifyBackendHomework\Application\Form\ProductForm;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Price;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Color;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Exception\ProductAlreadyExistsException;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductId;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductType;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Size;
use Money\Currency;
use Money\Money;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductRepositoryInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Product as PersistentProduct;
use KacperWojtaszczyk\PrintifyBackendHomework\Application\Helper\ExceptionHelper;
use Swagger\Annotations as SWG;

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
     *
     * @SWG\Tag(name="Product")
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="",
     *     required=true,
     *     @SWG\Schema (ref="#/definitions/create_product")
     * )
     * @SWG\Response(
     *     response="201",
     *     description="Zapisano",
     *     @SWG\Schema( ref="#/definitions/create_product_success")
     * )
     * @SWG\Response(
     *     response="400",
     *     description="Bad Request",
     *     @SWG\Schema( ref="#/definitions/error_message")
     * )
     * @SWG\Response(response="500", description="Server Error",
     *     @SWG\Schema( ref="#/definitions/error_message"))
     *
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
                $money = new Money(str_replace('.', '', $data->price), new Currency($this->currency));
                $price = Price::fromMoney($money);
                $productId = ProductId::fromProductTypeColorSize($productType, $color, $size);

                $product = PersistentProduct::withParameters($productId, $price, $productType, $color, $size);

                $this->productRepository->save($product);

                return JsonResponse::create(
                    [
                        'id' => (string) $product->getId(),
                        'price' => (string) $price,
                        'productType' => (string) $product->getProductType(),
                        'color' => (string) $product->getColor(),
                        'size' => (string) $product->getSize()
                    ],
                    Response::HTTP_CREATED);
            }
            else
            {
                $errors = [];
                foreach($form->getErrors(true, true) as $error)
                {
                    $errors[] = $error->getMessage();
                }
                return JsonResponse::create(
                    [
                        'code' => Response::HTTP_BAD_REQUEST,
                        'error' => $errors
                    ], Response::HTTP_BAD_REQUEST
                );
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