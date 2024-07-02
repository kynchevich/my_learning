<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

class ElasticsearchBookQueryBuilder
{
    private $bodyParams;
    private $toAllBodyParams = [];

    private $indexName;
    private $title;
    private $category;
    private $minPrice;

    private $maxPrice;
    private $shopName;
    private $minStock;

    public function __construct(string $indexName)
    {
        $this->indexName = $indexName;
        $this->toAllBodyParams = [
            'query' => [
                'match_all' => new \stdClass()
            ]
        ];
    }

    public function build():self
    {
        if (isset($this->title)) {
            $this->bodyParams['query']['bool']['must'][] = ['match' => ['title' => $this->title]];
        }

        if (isset($this->category)) {
            $this->bodyParams['query']['bool']['must'][] = ['match' => ['category' => $this->category]];
        }

        if (isset($this->minPrice) && isset($this->maxPrice)) {
            $this->bodyParams['query']['bool']['must'][] = ['range' => ['price' => ['gte' => $this->minPrice, 'lte' => $this->maxPrice]]];
        } elseif (isset($this->minPrice)) {
            $this->bodyParams['query']['bool']['must'][] = ['range' => ['price' => ['gte' => $this->minPrice]]];
        } elseif (isset($this->maxPrice)) {
            $this->bodyParams['query']['bool']['must'][] = ['range' => ['price' => ['lte' => $this->maxPrice]]];
        }

        if (isset($this->shopName)) {
            $this->bodyParams['query']['bool']['must'][] = ['match' => ['shop_name' => $this->shopName]];
        }

        if (isset($this->minStock)) {
            $this->bodyParams['query']['bool']['must'][] = ['range' => ['stock_quantity' => ['gte' => $this->minStock]]];
        }

        return $this;
    }

    public function getBodyParams() :array
    {
        return $this->bodyParams ?? $this->toAllBodyParams;
    }

    public function getQuery(): array
    {
        return [
            'index' => $this->indexName,
            'body' => $this->getBodyParams()
        ];
    }


     /**
     * @param string $title
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $category
     */
    public function setCategory(?string $category): self
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @param int $minPrice
     */
    public function setMinPrice(?int $minPrice): self
    {
        $this->minPrice = $minPrice;
        return $this;
    }

    /**
     * @param int $maxPrice
     */
    public function setMaxPrice(?int $maxPrice): self
    {
        $this->maxPrice = $maxPrice;
        return $this;
    }

    /**
     * @param string $shopName
     */
    public function setShopName(?string $shopName): self
    {
        $this->shopName = $shopName;
        return $this;
    }

    /**
     * @param int $minStock
     */
    public function setMinStock(?int $minStock): self
    {
        $this->minStock = $minStock;
        return $this;
    }

}