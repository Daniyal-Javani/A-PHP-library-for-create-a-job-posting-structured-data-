<?php
/**
 * This is an interface for JobPosting type of google structured data.
 */
namespace JobPosting;

interface Type
{
    /**
     * Specify data which should be serialized to JSON
     * @return array Returns data which can be serialized by json_encode(), which is a value of any type other than a resource.
     */
    public function jsonSerialize();

    /**
     * Prepare the final result as a script to add to a page
     * @return string The complete script tag
     */
    public function toScript();

    /**
     * Set the base salary property
     * @param string $currency   The currency of the salary
     * @param string $unitText   The unit of the salary it's case-sensitive and the allowed values are: HOUR, DAY, WEEK, MONTH, YEAR
     * @param numeric $value     The amound of salary or the minimum amount of it
     * @param numeric $maxValue  The maximum amount of salary if you have defined the minimum one
     */
    public function setBaseSalary($currency, $unitText, $value, $maxValue = null);

    /**
     * Set the posted date
     * @param string $datePosted The original date that employer posted the job in ISO 8601 format
     */
    public function setDatePosted($datePosted);

    /**
     * Set the description
     * @param string $description The full description of the job in HTML format.
     */
    public function setDescription($description);

    /**
     * Set the type of employment
     * @param string $employmentType Type of employment it's case-sensitive and the allowed values are: FULL_TIME, PART_TIME, CONTRACTOR, TEMPORARY, INTERN, VOLUNTEER, PER_DIEM, OTHER
     */
    public function setEmploymentType($employmentType);

    /**
     * Set the details about the organization offering the job position.
     * @param string $name   The name of the organization
     * @param string $sameAs The URL of the organization
     * @param string $logo   The URL of it's logo
     */
    public function setHiringOrganization($name, $sameAs, $logo = null);

    /**
     * Set the hiring organization's unique identifier for the job.
     * @param  string $name  The name of the organization
     * @param  numeric $value The nueric value of it
     */
    public function setidentifier($name, $value);

    /**
     * Set the geographic location where the employee will primarily work
     * @param string  $streetAddress   The street address
     * @param string  $addressLocality The locality address
     * @param string  $addressRegion   The region of the address
     * @param numeric  $postalCode     The postal code
     * @param string  $addressCountry  The country address
     * @param boolean $TELECOMMUTE     Set it true if it's a remote job
     */
    public function setJobLocation($streetAddress, $addressLocality, $addressRegion, $postalCode, $addressCountry, $TELECOMMUTE = false);

    /**
     * Set the title of the job
     * @param string $title The title
     */
    public function setTitle($title);

    /**
     * Set the date when the job posting will expire in ISO 8601 format.
     * @param string $validThrough The date
     */
    public function setValidThrough($validThrough);

    /**
     * The getter method to get and check the property
     * @param  string $name The name of the property
     * @return The requested value
     */
    public function __get($name);

    /**
     * Set additional property
     * @param string $name  The name of the property
     * @param string $value The value
     */
    public function __set($name, $value);

    /**
     * Check if there is a value
     * @param  string  $name The name of the value
     * @return boolean       It's true if there is
     */
    public function __isset($name);

    /**
     * Remove the property
     * @param string $name The name of the property
     */
    public function __unset($name);
}
