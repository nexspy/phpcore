<?php

/**
 * LicenceReader
 * 
 * @author 		Nirmal Limbu
 */

class LicenceReader
{
    private $filename;
    private $dbConn;

    /**
     * Constructor
     * @param string $filename
     *   The name of the file to read data from.
     */
    public function __construct($filename, $dbConn)
    {
        $this->filename = $filename;
        $this->dbConn = $dbConn;
    }

    /**
     * Read : json file and return the content
     */
    public function readLicence()
    {
        $licence = [];
        $result = [
            'licence' => $licence,
            'success' => false,
            'message' => 'No licence found',
        ];

        try {
            if (file_exists($this->filename)) {
                $json = file_get_contents($this->filename);
                $data = json_decode($json, true);

                if (isset($data['TextDetections'])) {
                    $licence = $this->prepareData($data['TextDetections']);

                    $result['licence'] = $licence;
                    $result['success'] = true;
                    $result['message'] = 'Licences found';
                }
            } else {
                $result['message'] = 'File not found';
            }
        } catch (Exception $e) {
            $result['success'] = false;
            $result['message'] = $e->getMessage();
        }
        
        return $result;
    }

    /**
     * Prepare : convert data to readable format and easy to save to database
     * @param array $data
     * @return array
     */
    public function prepareData($data) {
        $result = [];

        foreach ($data as $key=>$item) {
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

    /**
     * Save : list of licences to database table "driver"
     */
    public function saveLicences($items) {
        foreach ($items as $item) {
            $this->saveLicence($item);
        }
    }
    
    /**
     * Save : list of licences to json file
     */
    public function saveLicence($item) {
        $result = [
            'success' => false,
            'message' => 'No licence found',
        ];

        try {
            $data = [
                'firstname' => $item['firstname'],
                'surname' => $item['surname'],
                'address_line_1' => $item['address_line_1'],
                'address_line_2' => $item['address_line_2'],
                'address_postcode' => $item['address_postcode'],
                'licence_number' => $item['licence_number'],
                'date_of_birth' => $item['date_of_birth'],
            ];
            
            $id = $this->dbConn-> insert('driver', $data);

            $result['result'] = $id;
            $result['licence'] = $item;
            $result['success'] = true;
            $result['message'] = 'Licence saved';
        } catch (Exception $e) {
            $result['success'] = false;
            $result['message'] = $e->getMessage();
        }
        
        return $result;
    }

    /**
     * Get : list of drivers of over the age and total sum of jobs they have done in given month and year
     * @param int $age
     * @param int $dateMonth
     * @param int $dateYear
     * @return array
     */
    public function getDrivers($ageLimit=0, $dateMonth=1, $dateYear=0) {
        $current_year = date('Y-m-d');

        if ($dateYear <= 0 ) {
            $dateYear = date('Y');
        }

        $query = 'SELECT id,date_of_birth FROM driver ';

        $result = $this->dbConn->fetchRowMany($query);

        $drivers = [];
        foreach ($result as $row) {
            $current_year = (int) date('Y');
            $date_of_birth = (int) date('Y', strtotime($row['date_of_birth']));
            $age = $current_year - $date_of_birth;
            if ($age > $ageLimit || $ageLimit == 0) {
                $driver['id'] = $row['id'];
                $driver['age'] = $age;
                $driver['total'] = $this->getTotalJobsPayment($row['id'], $dateMonth, $dateYear);
                $drivers[] = $driver;
            }
        }

        return $drivers;
    }

    /**
     * Get : total number of job of a give driver
     * @param int $id
     * @param int $month
     * @param int $year
     * @return int
     */
    public function getTotalJobsPayment($id, $month, $year) {
        // handle 01 and 11
        if ($month < 10) {
            $month = '0'.$month;
        }
        
        $query = 'SELECT SUM(amount) as total FROM jobs WHERE driver = ' . $id . ' AND date LIKE "' . $year . '-' . $month . '%"';
        $result = $this->dbConn->fetchRow($query);

        return (isset($result['total'])) ? $result['total'] : 0;
    }
}