<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var string テスト対象APIのバージョン v1:手続き型, v2:オブジェクト指向
     */
    // private $ver = 'v1';
    private $ver = 'v2';

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * @test
     * @dataProvider provideSuccessTestParams
     */
    public function 正常テスト($param, $expect)
    {
        $response = $this->postJson("/api/{$this->ver}/order", $param);

        $response
            ->assertStatus(200)
            ->assertExactJson($expect);
    }

    /**
     * 正常テスト用のパラメータ
     */
    public function provideSuccessTestParams()
    {
        return [
            '一般_7の日でない_5000円未満' => [
                [
                    'customer_id' => 1,
                    'order_datetime' => '2021-06-26 14:00:00',
                    'items' => [
                        ['item_id' => 1, 'quantity' => 2],
                        ['item_id' => 2, 'quantity' => 3],
                    ],
                ],
                [
                    'result' => true,
                    'message' => '正常終了',
                    'data' => [
                        'order' => [
                            'order_id' => 1,
                            'customer_id' => 1,
                            'customer_name' => 'マイケル',
                            'datetime' => '2021-06-26 14:00:00',
                            'subtotal_price' => 880,
                            'subtotal_in_sales_tax' => 80,
                            'discount' => 0,
                            'shipping' => 500,
                            'total_price' => 1380,
                            'total_in_sales_tax' => 125,
                            'order_details' => [
                                [
                                    'item_id' => 1,
                                    'item_name' => 'トマト',
                                    'category_name' => '野菜',
                                    'unit_price' => 110,
                                    'unit_in_tax' => 10,
                                    'quantity' => 2,
                                    'order_price' => 220,
                                    'order_in_tax' => 20,
                                ],
                                [
                                    'item_id' => 2,
                                    'item_name' => 'りんご',
                                    'category_name' => '果物',
                                    'unit_price' => 220,
                                    'unit_in_tax' => 20,
                                    'quantity' => 3,
                                    'order_price' => 660,
                                    'order_in_tax' => 60,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            '一般_7の日_5000円未満' => [
                [
                    'customer_id' => 1,
                    'order_datetime' => '2021-06-27 14:00:00',
                    'items' => [
                        ['item_id' => 1, 'quantity' => 2],
                        ['item_id' => 2, 'quantity' => 3],
                    ],
                ],
                [
                    'result' => true,
                    'message' => '正常終了',
                    'data' => [
                        'order' => [
                            'order_id' => 2,
                            'customer_id' => 1,
                            'customer_name' => 'マイケル',
                            'datetime' => '2021-06-27 14:00:00',
                            'subtotal_price' => 880,
                            'subtotal_in_sales_tax' => 80,
                            'discount' => 40,
                            'shipping' => 500,
                            'total_price' => 1340,
                            'total_in_sales_tax' => 121,
                            'order_details' => [
                                [
                                    'item_id' => 1,
                                    'item_name' => 'トマト',
                                    'category_name' => '野菜',
                                    'unit_price' => 110,
                                    'unit_in_tax' => 10,
                                    'quantity' => 2,
                                    'order_price' => 220,
                                    'order_in_tax' => 20,
                                ],
                                [
                                    'item_id' => 2,
                                    'item_name' => 'りんご',
                                    'category_name' => '果物',
                                    'unit_price' => 220,
                                    'unit_in_tax' => 20,
                                    'quantity' => 3,
                                    'order_price' => 660,
                                    'order_in_tax' => 60,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            '一般_7の日でない_5000円以上' => [
                [
                    'customer_id' => 1,
                    'order_datetime' => '2021-06-26 14:00:00',
                    'items' => [
                        ['item_id' => 3, 'quantity' => 10],
                        ['item_id' => 2, 'quantity' => 10],
                    ],
                ],
                [
                    'result' => true,
                    'message' => '正常終了',
                    'data' => [
                        'order' => [
                            'order_id' => 3,
                            'customer_id' => 1,
                            'customer_name' => 'マイケル',
                            'datetime' => '2021-06-26 14:00:00',
                            'subtotal_price' => 5500,
                            'subtotal_in_sales_tax' => 500,
                            'discount' => 0,
                            'shipping' => 0,
                            'total_price' => 5500,
                            'total_in_sales_tax' => 500,
                            'order_details' => [
                                [
                                    'item_id' => 3,
                                    'item_name' => 'ルイボスティー',
                                    'category_name' => '飲料',
                                    'unit_price' => 330,
                                    'unit_in_tax' => 30,
                                    'quantity' => 10,
                                    'order_price' => 3300,
                                    'order_in_tax' => 300,
                                ],
                                [
                                    'item_id' => 2,
                                    'item_name' => 'りんご',
                                    'category_name' => '果物',
                                    'unit_price' => 220,
                                    'unit_in_tax' => 20,
                                    'quantity' => 10,
                                    'order_price' => 2200,
                                    'order_in_tax' => 200,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            '一般_7の日_5000円以上' => [
                [
                    'customer_id' => 1,
                    'order_datetime' => '2021-06-27 14:00:00',
                    'items' => [
                        ['item_id' => 3, 'quantity' => 10],
                        ['item_id' => 2, 'quantity' => 10],
                    ],
                ],
                [
                    'result' => true,
                    'message' => '正常終了',
                    'data' => [
                        'order' => [
                            'order_id' => 4,
                            'customer_id' => 1,
                            'customer_name' => 'マイケル',
                            'datetime' => '2021-06-27 14:00:00',
                            'subtotal_price' => 5500,
                            'subtotal_in_sales_tax' => 500,
                            'discount' => 250,
                            'shipping' => 0,
                            'total_price' => 5250,
                            'total_in_sales_tax' => 477,
                            'order_details' => [
                                [
                                    'item_id' => 3,
                                    'item_name' => 'ルイボスティー',
                                    'category_name' => '飲料',
                                    'unit_price' => 330,
                                    'unit_in_tax' => 30,
                                    'quantity' => 10,
                                    'order_price' => 3300,
                                    'order_in_tax' => 300,
                                ],
                                [
                                    'item_id' => 2,
                                    'item_name' => 'りんご',
                                    'category_name' => '果物',
                                    'unit_price' => 220,
                                    'unit_in_tax' => 20,
                                    'quantity' => 10,
                                    'order_price' => 2200,
                                    'order_in_tax' => 200,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'VIP_7の日でない_5000円未満' => [
                [
                    'customer_id' => 2,
                    'order_datetime' => '2021-06-26 14:00:00',
                    'items' => [
                        ['item_id' => 1, 'quantity' => 2],
                        ['item_id' => 2, 'quantity' => 3],
                    ],
                ],
                [
                    'result' => true,
                    'message' => '正常終了',
                    'data' => [
                        'order' => [
                            'order_id' => 5,
                            'customer_id' => 2,
                            'customer_name' => 'ジョン',
                            'datetime' => '2021-06-26 14:00:00',
                            'subtotal_price' => 880,
                            'subtotal_in_sales_tax' => 80,
                            'discount' => 40,
                            'shipping' => 0,
                            'total_price' => 840,
                            'total_in_sales_tax' => 76,
                            'order_details' => [
                                [
                                    'item_id' => 1,
                                    'item_name' => 'トマト',
                                    'category_name' => '野菜',
                                    'unit_price' => 110,
                                    'unit_in_tax' => 10,
                                    'quantity' => 2,
                                    'order_price' => 220,
                                    'order_in_tax' => 20,
                                ],
                                [
                                    'item_id' => 2,
                                    'item_name' => 'りんご',
                                    'category_name' => '果物',
                                    'unit_price' => 220,
                                    'unit_in_tax' => 20,
                                    'quantity' => 3,
                                    'order_price' => 660,
                                    'order_in_tax' => 60,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @test
     */
    public function バリデーションエラーテスト()
    {
        $response = $this->postJson('/api/v1/order');

        $response->assertStatus(400);

        $response->dump();
    }
}
