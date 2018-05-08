<?php
use JobPosting\JobPostingFactory;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../vendor/autoload.php';

class StackTest extends TestCase
{
    public function testScript()
    {
        $factory = new JobPostingFactory();
        $JobPost = $factory->makeJobPost();
        $JobPost->setBaseSalary('EURO', 'HOUR', 40.0);
        $JobPost->setDatePosted('2016-02-18');
        $JobPost->setDescription('<p>Widget assembly role for pressing wheel assemblies.</p>
    <p><strong>Educational Requirements:</strong> Completed level 2 ISTA
    Machinist Apprenticeship.</p>
    <p><strong>Required Experience:</strong> At
    least 3 years in a machinist role.</p>');
        $JobPost->setEmploymentType('CONTRACTOR');
        $JobPost->setHiringOrganization('MagsRUs Wheel Company', 'http://www.magsruswheelcompany.com', 'http://www.example.com/images/logo.png');
        $JobPost->setidentifier('MagsRUs Wheel Company', '1234567');
        $JobPost->setJobLocation('555 Clancy St', 'Detroit', 'MI', '48201', 'US');
        $JobPost->setTitle('Fitter and Turner');
        $JobPost->setValidThrough('2017-03-18');

        $actual = $JobPost->toScript();
        $expected = '<script type="application/ld+json">{"@context":"http://schema.org/","@type":"JobPosting","baseSalary":{"@type":"MonetaryAmount","currency":"EURO","value":{"@type":"QuantitativeValue","value":40,"unitText":"HOUR"}},"datePosted":"2016-02-18","description":"<p>Widget assembly role for pressing wheel assemblies.</p>\n    <p><strong>Educational Requirements:</strong> Completed level 2 ISTA\n    Machinist Apprenticeship.</p>\n    <p><strong>Required Experience:</strong> At\n    least 3 years in a machinist role.</p>","employmentType":"CONTRACTOR","hiringOrganization":{"@type":"Organization","name":"MagsRUs Wheel Company","sameAs":"http://www.magsruswheelcompany.com","logo":"http://www.example.com/images/logo.png"},"identifier":{"@type":"PropertyValue","name":"MagsRUs Wheel Company","value":"1234567"},"jobLocation":{"@type":"Place","address":{"@type":"PostalAddress","streetAddress":"555 Clancy St","addressLocality":"Detroit","addressRegion":"MI","postalCode":"48201","addressCountry":"US"}},"title":"Fitter and Turner","validThrough":"2017-03-18"}</script>';
        $this->assertSame($expected, $actual);
    }
}
