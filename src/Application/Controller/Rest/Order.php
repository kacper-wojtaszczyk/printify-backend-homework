<?php
declare(strict_types=1);


namespace KacperWojtaszczyk\PrintifyBackendHomework\Application\Controller\Rest;


use KacperWojtaszczyk\PrintifyBackendHomework\Application\Exception\IncorrectOrderTotalException;
use KacperWojtaszczyk\PrintifyBackendHomework\Application\Exception\TooManyOrdersFromCountryException;
use KacperWojtaszczyk\PrintifyBackendHomework\Application\Form\Model\OrderFormModel;
use KacperWojtaszczyk\PrintifyBackendHomework\Application\Form\OrderForm;
use KacperWojtaszczyk\PrintifyBackendHomework\Application\Helper\ExceptionHelper;
use KacperWojtaszczyk\PrintifyBackendHomework\Infrastructure\Provider\CountryProviderInterface;
use KacperWojtaszczyk\PrintifyBackendHomework\Infrastructure\Provider\IpAddress;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\OrderId;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\OrderItem;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\OrderRepositoryInterface;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\OrderRequest;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\OrderRequestRepositoryInterface;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Exception\ProductNotFoundException;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductId;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductRepositoryInterface;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductType;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\User;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;


class Order
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
     * @var CountryProviderInterface
     */
    private $countryProvider;

    /**
     * @var OrderRequestRepositoryInterface
     */
    private $orderRequestRepository;

    /**
     * @var int
     */
    private $limitPerCountryPerSecond;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    public function __construct(
        FormFactoryInterface $formFactory,
        ProductRepositoryInterface $productRepository,
        CountryProviderInterface $countryProvider,
        OrderRequestRepositoryInterface $orderRequestRepository,
        OrderRepositoryInterface $orderRepository,
        string $limitPerCountryPerSecond
    ) {
        $this->formFactory = $formFactory;
        $this->productRepository = $productRepository;
        $this->countryProvider = $countryProvider;
        $this->orderRequestRepository = $orderRequestRepository;
        $this->limitPerCountryPerSecond = (int) $limitPerCountryPerSecond;
        $this->orderRepository = $orderRepository;
    }

    /**
     *
     * @SWG\Tag(name="Order")
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="",
     *     required=true,
     *     @SWG\Schema (ref="#/definitions/create_order")
     * )
     * @SWG\Response(
     *     response="201",
     *     description="Zapisano",
     *     @SWG\Schema( ref="#/definitions/create_order_success")
     * )
     * @SWG\Response(
     *     response="400",
     *     description="Bad Request",
     *     @SWG\Schema( ref="#/definitions/error_message")
     * )
     * @SWG\Response(response="500", description="Server Error",
     *     @SWG\Schema( ref="#/definitions/error_message"))
     *
     * @Route("/order", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $model = new OrderFormModel();

        $form = $this->formFactory->create(OrderForm::class, $model);
        try {
            $country = $this->countryProvider->findByIp(new IpAddress($request->getClientIp()));
            $orderRequest = OrderRequest::withParameters($country, new \DateTime());
            $this->orderRequestRepository->save($orderRequest);
            $lastSecondRequests = $this->orderRequestRepository->findByDateCountry(new \DateTime(), $country);
            if($lastSecondRequests !== null && $lastSecondRequests->count() > $this->limitPerCountryPerSecond)
            {
                throw TooManyOrdersFromCountryException::withCountry($country);
            }

            $form->submit($request->request->all());
            if ($form->isValid()) {
                $data = $form->getData();

                $orderId = OrderId::generate();


                $order = \KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\Order::withParameters(
                    $orderId,
                    $country
                );

                foreach ($data->items as $item)
                {
                    if(!$product = $this->productRepository->findOneById(ProductId::fromString($item->productId)))
                    {
                        throw ProductNotFoundException::withProductId(ProductId::fromString($item->productId));
                    }
                    $price = $product->getPrice();
                    $orderItem = OrderItem::withParameters($order, $product, $price, (int) $item->quantity);
                    $order->addOrderItem($orderItem);
                }

                if($order->getTotal()->getAmount() < 10*100)
                {
                    throw IncorrectOrderTotalException::withPrice($order->getTotal());
                }

                $this->orderRepository->save($order);

                return JsonResponse::create(
                    $order,
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

    /**
     *
     * @SWG\Tag(name="Order")
     *
     *
     * @SWG\Response(
     *     response="200",
     *     description="Request completed",
     *     @SWG\Schema( ref="#/definitions/list_order_success")
     * )
     * @SWG\Response(
     *     response="400",
     *     description="Bad Request",
     *     @SWG\Schema( ref="#/definitions/error_message")
     * )
     * @SWG\Response(response="500", description="Server Error",
     *     @SWG\Schema( ref="#/definitions/error_message"))
     *
     * @Route("/order", methods={"GET"})
     */
    public function list(Request $request): Response
    {
        $orders = $this->orderRepository->findAll();
        return new JsonResponse($orders->toArray(), Response::HTTP_OK);
    }

    /**
     *
     * @SWG\Tag(name="Order")
     *
     * @SWG\Parameter(
     *     name="productType",
     *     type="string",
     *     in="path",
     *     description="",
     *     required=false,
     * )
     *
     * @SWG\Response(
     *     response="200",
     *     description="Request completed",
     *     @SWG\Schema( ref="#/definitions/list_order_success")
     * )
     * @SWG\Response(
     *     response="400",
     *     description="Bad Request",
     *     @SWG\Schema( ref="#/definitions/error_message")
     * )
     * @SWG\Response(response="500", description="Server Error",
     *     @SWG\Schema( ref="#/definitions/error_message"))
     *
     * @Route("/order/{productType}", methods={"GET"})
     */
    public function listByType(Request $request, string $productType = null): Response
    {
        $orders = $this->orderRepository->findByProductType(new ProductType($productType));
        return new JsonResponse($orders->toArray(), Response::HTTP_OK);
    }

}