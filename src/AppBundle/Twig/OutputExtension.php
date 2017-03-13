<?php

namespace AppBundle\Twig;

class OutputExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('mutlidimensional_join', array($this, 'multidimensionalJoin')),
        );
    }

    public function multidimensionalJoin(array $array, $key_delimiter = ':', $entry_delimiter = ',', $level_delimiter = ';')
    {
        $joined = [];
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                if (is_array($v[0])) {
                    foreach ($v as $sub_v) {
                        if (is_array($sub_v)) {
                            $joined[] = $this->multidimensionalJoin($sub_v, $key_delimiter, $entry_delimiter, $level_delimiter);
                        } else {
                            $joined[] = $sub_v;
                        }
                    }
                } else {
                    $joined[] = $k . $key_delimiter . join($entry_delimiter, $v);
                }
            }
        }

        return join($level_delimiter, $joined);
    }
}