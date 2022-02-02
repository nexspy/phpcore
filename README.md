# PHP Technical Test

## Docker Notes

1. Create Dockerfile
It should contain **FROM** image.
**RUN** install what we need like php extensions
**COPY** our file to container.

2. Build Image
Run following command to build an image based on our Dockerfile.
```
docker build -t php-docker .
```

3. Create and run container
Run following command to run container with given image name. This tells to use port 8000 on host (my computer) and 80 on the container. **php-docker** is the name of the image.
```
docker run -p 8000:80 php-docker
```

4. Create and run mysql container
This creates a new container for running mysql server. We have also set environment variable using **-e**.
We have also set volume to /var/lib/mysql location of the container.
If the images are not present, the docker will fetch it from the docker hub.
```
docker run -p 33060:3306 -e MYSQL_ALLOW_EMPTY_PASSWORD=yes -v db-volume:/var/lib/mysql --name db mysql:5.7
```



## Running a php script 

```docker
FROM php:7.4-cli
COPY . /usr/src/myapp
WORKDIR /usr/src/myapp
CMD [ "php", "./index.php" ]
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