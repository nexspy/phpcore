<?php

class Licence {
    private $firstname;
    private $surname;
    private $address_line_1;
    private $address_line_2;
    private $address_postcode;
    private $licence_number;
    private $date_of_birth;

    public function __construct($data) {

        $info = $this->parseData($data);

        $this->firstname = $info['firstname'];
        $this->surname = $info['surname'];
        $this->address_line_1 = $info['address_line_1'];
        $this->address_line_2 = $info['address_line_2'];
        $this->address_postcode = $info['address_postcode'];
        $this->licence_number = $info['licence_number'];
        $this->date_of_birth = $info['date_of_birth'];
    }

    public function getData() {
        return [
            'firstname' => $this->firstname,
            'surname' => $this->surname,
            'address_line_1' => $this->address_line_1,
            'address_line_2' => $this->address_line_2,
            'address_postcode' => $this->address_postcode,
            'licence_number' => $this->licence_number,
            'date_of_birth' => $this->date_of_birth,
        ];
    }

    /**
     * Prepare : convert data to readable format and easy to save to database
     * @param array $data
     * @return array
     */
    public function parseData($rawData) {
        $result = [];
    
        foreach ($rawData as $key=>$item) {
            if ($key === 5) {
                $result['firstname'] = $item['DetectedText'];
            } else if ($key === 3) {
                $result['surname'] = $item['DetectedText'];
            } else if ($key === 18) {
                $result['address_line_1'] = $item['DetectedText'];
            } else if ($key === 19) {
                $result['address_line_2'] = $item['DetectedText'];
            } else if ($key === 20) {
                $result['address_postcode'] = $item['DetectedText'];
            } else if ($key === 45) {
                $result['licence_number'] = $item['DetectedText'];
            } else if ($key === 34) {
                $result['date_of_birth'] = date('Y-d-m', strtotime($item['DetectedText']));
            }
        }

        return $result;
    }
}