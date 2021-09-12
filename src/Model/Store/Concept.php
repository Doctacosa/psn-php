<?php
namespace Tustin\PlayStation\Model\Store;

use GuzzleHttp\Client;
use Tustin\PlayStation\Api;
use Tustin\PlayStation\Traits\Model;
use Tustin\PlayStation\Interfaces\Fetchable;

class Concept extends Api implements Fetchable
{
    use Model;

    private $conceptId;
    
	public function __construct(Client $client, string $conceptId)
	{
		parent::__construct($client);

		$this->conceptId = $conceptId;
	}

    public static function fromObject(Client $client, object $data)
    {
        $instance = new Concept($client, $data->conceptId);
        $instance->setCache($data);

        return $instance;
    }

    public function productId() : string
    {
        return $this->pluck('id');
    }

    public function name() : string
    {
        return $this->pluck('name');
    }

    public function conceptId() : string
    {
        return $this->conceptId;
    }

    public function publisher() : string
    {
        return ($this->pluck('publisherName') ?? $this->pluck('leadPublisherName'));
    }

	public function fetch() : object
    {
        return $this->graphql('metGetConceptById', [
            'conceptId' => $this->conceptId(),
            'productId' => ''
        ])->conceptRetrieve;
    }
}