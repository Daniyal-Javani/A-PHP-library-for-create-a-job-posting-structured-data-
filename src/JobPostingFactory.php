<?php
namespace JobPosting;

class JobPostingFactory implements StructuredDataFactory
{
    public function makeJobPost()
    {
        return new JobPosting();
    }
}
