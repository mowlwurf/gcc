<?php

namespace AppBundle\Service;


class ReadLineService
{
    protected $completionList;
    /**
     * @var string
     */
    private $input;

    /**
     * ReadLineService constructor.
     * @param array $completionList
     */
    public function __construct(array $completionList = [])
    {
        if (!empty($completionList)) {
            $this->setCompletionList($completionList);
        }
    }

    /**
     * @param array $completionList
     */
    public function setCompletionList(array $completionList)
    {
        $this->completionList = $completionList;
    }

    /**
     * @return void
     */
    public function setCallback()
    {
        readline_completion_function(function($input, $index){;
            // Filter Matches
            $matches = array();
            foreach($this->completionList as $branch)
                if(stripos($branch, $input) === 0)
                    $matches[] = $branch;

            // Prevent Segfault
            if(empty($matches))
                $matches = $this->completionList;

            return $matches;
        });
    }

    /**
     * @param string $preInput
     * @return string
     */
    public function readLine($preInput = '')
    {
        $this->input = readline(sprintf(
            "%s: ",
            $preInput
        ));
        return $this->input;
    }
}