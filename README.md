# PHP Technical Test

## Task 1
The JSON file ocr.json contains the output of a scan of driving-licence.jpeg which was performed by a web service that can identify text in images.
You should create a class with a method that extracts the following data from the ocr.json file and inserts the data into the driver table of the database.

- Firstname
- Surname
- Address Line 1
- Address Line 2
- Address Postcode
- Licence Number
- Date of birth


## Task 2
Create another method in your class to retrieve a list of drivers who are over 50, and the total sum of the jobs they have done in December 2021 from the database.

Your output should resemble

> Driver 1 = £21.98
> Driver 2 = £87.21 


## Used Packages

We have used simplon mysql package for connecting to the MySQL database.

```terminal
> composer require simplon/mysql
```


## Extra

    /**
     * Get : total number of job of a give driver
     */
    public function getTotalJobs($id, $month, $year) {

        // handle 01 and 11
        if ($month < 10) {
            $month = '0'.$month;
        }
        // echo $month; die;

        $query = 'SELECT COUNT(driver) AS total_jobs FROM jobs WHERE driver = ' . $id . ' AND date LIKE "' . $year . '-' . $month . '%"';
        $result = $this->dbConn->fetchRow($query);
        
        return $result['total_jobs'];
    }