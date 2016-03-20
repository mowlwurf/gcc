<?php

namespace AppBundle\Service;


class GitService
{
    /**
     * @param string $input
     */
    public function checkout($input)
    {
        if (in_array(trim($input), $this->getBranches())){
            print shell_exec('git checkout ' . $input);
        } else {
            print "$input seems not to be an existing branch, please use git checkout -b $input to create a new branch";
        }
    }

    /**
     * @return array
     */
    public function getBranches()
    {
        $output = shell_exec('git branch -a');
        return $this->parseCliOutput($output);
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->parseCliOutput(shell_exec('git tag -l'));
    }

    /**
     *
     * Parses cli output to array, duplicates are removed
     *
     * @param string $output
     * @return array
     */
    public function parseCliOutput($output)
    {
        $arr = explode("\n", $output);
        $branches = [];
        foreach ($arr as $key => $branchLine) {
            $branchLine = str_replace('remotes/origin/','',$branchLine);
            $branchLine = trim($branchLine, ' *');
            $arr[$key] = $branchLine;
            if (empty($arr[$key])) {
                unset($arr[$key]);
                continue;
            }
            $branches[$arr[$key]] = $branchLine;
        }
        return $branches;
    }
}