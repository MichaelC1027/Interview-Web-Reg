<?php

use Aws\DynamoDb\Marshaler;

class Database{

    public $marshaler;
    protected $connection;

    public function __construct()
    {
        $this->connectDB();
    }

    protected function connectDB()
    {
        //create instance of DynamoDB client
        try{
            $this->connection = Aws\DynamoDb\DynamoDbClient::factory(array(
            'region' => 'us-east-1',
            'version' => 'latest',
            'credentials' => array(
                'key' => getenv('aws_key'),
                'secret'  => getenv('aws_secret'),
            )
        ));
        $this->marshaler = new Aws\DynamoDb\Marshaler();
        }catch(Exception $e){
            throw new Exception('Failed to connect');
        }
    }

    public function addItem($info)
    {
        try{
            if(!isset($info['id'])){
                $info['id'] = $this->createId();
            }
            //create new item to add to table
            $item = $this->marshaler->marshalItem($info);

            $params = [
                'TableName' => 'michaelcastillo2022',
                'Item' => $item
            ];

            $result = $this->connection->putItem($params);
            
        } catch(Exception $e){
            throw new Exception('Failed to add item');
        }

        return $result;
    }

    public function queryDB($query)
    {
        try{
            $query['TableName'] = 'michaelcastillo2022';
            $query['ExpressionAttributeValues'] = $this->marshaler->marshalItem($query['ExpressionAttributeValues']);
            
            $result = $this->connection->query($query);
            $resultArray = [];

            foreach($result['Items'] as $i){
                $resultArray[] = $i;
            }
            
        } catch(Exception $e){
            throw new Exception('Failed to query');
        }

        $total = count($resultArray);
        return $total;
    }

    public function createId(){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 16; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
