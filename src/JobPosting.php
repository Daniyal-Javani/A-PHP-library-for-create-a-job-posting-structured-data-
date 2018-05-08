<?php
namespace JobPosting;

class JobPosting implements Type, \JsonSerializable
{
    /**
     * The main array to store properties and convert it to the script
     * @var array
     */
    private $data = array();

    public function __construct()
    {
        $this->data['@context'] = 'http://schema.org/';
        $this->data['@type'] = 'JobPosting';

    }

    /**
     * Specify data which should be serialized to JSON
     * @return array Returns data which can be serialized by json_encode(), which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return $this->data;
    }

    /**
     * Trigger errors
     * @param  array $errors An associative array of property names and it's errors
     */
    private function showErrors($errors)
    {
        foreach ($errors as $key => $value) {
            trigger_error(
                $key . ' ' . $value,
                E_USER_NOTICE);
        }
    }

    /**
     * Check the required properties
     * @return bool The validation result
     */
    private function checkRequirements()
    {
        $recommended = [];
        if (!isset($this->data['baseSalary'])) {
            $recommended[] = 'baseSalary';
        }
        if (!isset($this->data['employmentType'])) {
            $recommended[] = 'employmentType';
        }
        if (!isset($this->data['identifier'])) {
            $recommended[] = 'identifier';
        }
        foreach ($recommended as $value) {
            trigger_error(
                'Defining ' . $value . ' is recommended',
                E_USER_NOTICE);
        }
        $required = [];
        if (!isset($this->data['datePosted'])) {
            $required[] = 'datePosted';
        }
        if (!isset($this->data['description'])) {
            $required[] = 'description';
        }
        if (!isset($this->data['hiringOrganization'])) {
            $required[] = 'hiringOrganization';
        }
        if (!isset($this->data['jobLocation'])) {
            $required[] = 'jobLocation';
        }
        if (!isset($this->data['title'])) {
            $required[] = 'title';
        }
        if (!isset($this->data['validThrough'])) {
            $required[] = 'validThrough';
        }
        foreach ($required as $value) {
            trigger_error(
                'Defining ' . $value . ' is required',
                E_USER_NOTICE);
        }
    }

    /**
     * Prepare the final result as a script to add to a page
     * @return string The complete script tag
     */
    public function toScript()
    {
        $this->checkRequirements();
        return '<script type="application/ld+json">' . json_encode($this, JSON_UNESCAPED_SLASHES) . '</script>';
    }

    /**
     * Set additional property
     * @param string $name  The name of the property
     * @param string $value The value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * Set the base salary property
     * @param string $currency   The currency of the salary
     * @param string $unitText   The unit of the salary it's case-sensitive and the allowed values are: HOUR, DAY, WEEK, MONTH, YEAR
     * @param numeric $value     The amound of salary or the minimum amount of it
     * @param numeric $maxValue  The maximum amount of salary if you have defined the minimum one
     */
    public function setBaseSalary($currency, $unitText, $value, $maxValue = null)
    {
        $errors = [];
        if (!is_string($currency)) {
            $errors['currency'] = 'should be a string';
        }
        if (!is_numeric($value)) {
            $errors['value'] = 'should be a number';
        }
        if (isset($maxValue) && !is_numeric($value)) {
            $errors['maxValue'] = 'should be a number';
        }
        if (!in_array($unitText, ["HOUR", "DAY", "WEEK", "MONTH", "YEAR"])) {
            $errors['unitText'] = 'should be one of the HOUR, DAY, WEEK, MONTH or YEAR';
        }
        if (!empty($errors)) {
            $this->showErrors($errors);
            return false;
        }

        $this->data['baseSalary'] = array();
        $this->data['baseSalary']['@type'] = 'MonetaryAmount';
        $this->data['baseSalary']['currency'] = $currency;
        $this->data['baseSalary']['value'] = array();
        $this->data['baseSalary']['value']['@type'] = 'QuantitativeValue';
        if ($maxValue == null) {
            $this->data['baseSalary']['value']['value'] = $value;
        } else {
            $this->data['baseSalary']['value']['minValue'] = $value;
            $this->data['baseSalary']['value']['maxValue'] = $maxValue;
        }
        $this->data['baseSalary']['value']['unitText'] = $unitText;
    }

    /**
     * Set the posted date
     * @param string $datePosted The original date that employer posted the job in ISO 8601 format
     */
    public function setDatePosted($datePosted)
    {
        $tempDate = \DateTime::createFromFormat('Y-m-d', $datePosted);
        if (!($tempDate && $tempDate->format('Y-m-d') == $datePosted)) {
            $errors['date'] = 'should be in ISO 8601 format like 2016-02-18';
            $this->showErrors($errors);
            return false;
        }
        $this->data['datePosted'] = $datePosted;
    }

    /**
     * Set the description
     * @param string $description The full description of the job in HTML format.
     */
    public function setDescription($description)
    {
        if (!is_string($description)) {
            $errors['description'] = 'should be a string';
            $this->showErrors($errors);
            return false;
        }
        $this->data['description'] = $description;
    }

    /**
     * Set the type of employment
     * @param string $employmentType Type of employment it's case-sensitive and the allowed values are: FULL_TIME, PART_TIME, CONTRACTOR, TEMPORARY, INTERN, VOLUNTEER, PER_DIEM, OTHER
     */
    public function setEmploymentType($employmentType)
    {
        $validValues = ["FULL_TIME", "PART_TIME", "CONTRACTOR", "TEMPORARY", "INTERN", ",OLUNTEER", "PER_DIEM", "OTHER"];
        if (is_string($employmentType)) {
            if (!in_array($employmentType, $validValues)) {
                $errors['employmentType'] = 'value is not valid';
            }
        } else if (is_array($employmentType)) {
            if (count(array_intersect($employmentType, $validValues)) == count($employmentType)) {
                $errors['employmentType'] = 'values are not valid';
            }
        } else {
            $errors['employmentType'] = 'should be string or array';
        }

        if (!empty($errors)) {
            $this->showErrors($errors);
            return false;
        }
        $this->data['employmentType'] = $employmentType;
    }

    /**
     * Set the details about the organization offering the job position.
     * @param string $name   The name of the organization
     * @param string $sameAs The URL of the organization
     * @param string $logo   The URL of it's logo
     */
    public function setHiringOrganization($name, $sameAs, $logo = null)
    {
        $errors = [];
        if (!is_string($name)) {
            $errors['name'] = 'should be a string';
        }
        if (!is_string($sameAs)) {
            $errors['sameAs'] = 'should be a string';
        }
        if (!is_string($logo)) {
            $errors['logo'] = 'should be a string';
        }
        if (!empty($errors)) {
            $this->showErrors($errors);
            return false;
        }

        $this->data['hiringOrganization'] = array();
        $this->data['hiringOrganization']['@type'] = 'Organization';
        $this->data['hiringOrganization']['name'] = $name;
        $this->data['hiringOrganization']['sameAs'] = $sameAs;
        if ($logo != null) {
            $this->data['hiringOrganization']['logo'] = $logo;
        }
    }

    /**
     * Set the hiring organization's unique identifier for the job.
     * @param  string $name  The name of the organization
     * @param  numeric $value The nueric value of it
     */
    public function setidentifier($name, $value)
    {
        $errors = [];
        if (!is_string($name)) {
            $errors['name'] = 'should be a string';
        }
        if (!is_string($value)) {
            $errors['value'] = 'should be a string';
        }
        if (!empty($errors)) {
            $this->showErrors($errors);
            return false;
        }

        $this->data['identifier'] = array();
        $this->data['identifier']['@type'] = 'PropertyValue';
        $this->data['identifier']['name'] = $name;
        $this->data['identifier']['value'] = $value;
    }

    /**
     * The getter method to get and check the property
     * @param  string $name The name of the property
     * @return The requested value
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }

    /**
     * Set the geographic location where the employee will primarily work
     * @param string  $streetAddress   The street address
     * @param string  $addressLocality The locality address
     * @param string  $addressRegion   The region of the address
     * @param numeric  $postalCode     The postal code
     * @param string  $addressCountry  The country address
     * @param boolean $TELECOMMUTE     Set it true if it's a remote job
     */
    public function setJobLocation($streetAddress, $addressLocality, $addressRegion, $postalCode, $addressCountry, $TELECOMMUTE = false)
    {
        $errors = [];
        if (!is_string($streetAddress)) {
            $errors['streetAddress'] = 'should be a string';
        }
        if (!is_string($addressLocality)) {
            $errors['addressLocality'] = 'should be a string';
        }
        if (!is_string($addressRegion)) {
            $errors['addressRegion'] = 'should be a string';
        }
        if (!is_string($postalCode)) {
            $errors['postalCode'] = 'should be a string';
        }
        if (!is_string($addressCountry)) {
            $errors['addressCountry'] = 'should be a string';
        }
        if (!empty($errors)) {
            $this->showErrors($errors);
            return false;
        }

        $this->data['jobLocation'] = array();
        $this->data['jobLocation']['@type'] = 'Place';
        $this->data['jobLocation']['address'] = array();
        $this->data['jobLocation']['address']['@type'] = 'PostalAddress';
        $this->data['jobLocation']['address']['streetAddress'] = $streetAddress;
        $this->data['jobLocation']['address']['addressLocality'] = $addressLocality;
        $this->data['jobLocation']['address']['addressRegion'] = $addressRegion;
        $this->data['jobLocation']['address']['postalCode'] = $postalCode;
        $this->data['jobLocation']['address']['addressCountry'] = $addressCountry;
        if ($TELECOMMUTE == true) {
            $this->data['jobLocation']['additionalProperty'] = array();
            $this->data['jobLocation']['additionalProperty']['@type'] = 'PropertyValue';
            $this->data['jobLocation']['additionalProperty']['value'] = 'TELECOMMUTE';
        }
    }

    /**
     * Set the title of the job
     * @param string $title The title
     */
    public function setTitle($title)
    {
        if (!is_string($title)) {
            $errors['title'] = 'should be a string';
            $this->showErrors($errors);
            return false;
        }
        $this->data['title'] = $title;
    }

    /**
     * Set the date when the job posting will expire in ISO 8601 format.
     * @param string $validThrough The date
     */
    public function setValidThrough($validThrough)
    {
        $tempDate = \DateTime::createFromFormat('Y-m-d', $validThrough);
        if (!($tempDate && $tempDate->format('Y-m-d') == $validThrough)) {
            $errors['date'] = 'should be in ISO 8601 format like 2016-02-18';
            $this->showErrors($errors);
            return false;
        }
        $this->data['validThrough'] = $validThrough;
    }

    /**
     * Check if there is a value
     * @param  string  $name The name of the value
     * @return boolean       It's true if there is
     */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * Remove the property
     * @param string $name The name of the property
     */
    public function __unset($name)
    {
        unset($this->data[$name]);
    }
}
