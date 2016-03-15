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
            print shell_exec('git checkout '.$input);
        } else {
            print "$input seems not to be an existing branch, please use git checkout -b $input to create a new branch";
        }
    }

    public function getBranches()
    {
        $output = shell_exec('git branch -a');
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