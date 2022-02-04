<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-02-04 11:22:26
 * @modify date 2022-02-04 11:22:26
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Database\Query;

trait Error
{
    public function getSimpleError(string $ErrorMapKey = '')
    {
        $Map = ['SQLSTATE' => 0, 'DriverErrorCode' => 1, 'DriverErrorMessage' => 2];

        return (!empty($ErrorMapKey) && isset($Map[$ErrorMapKey])) ? $this->Error['simple'][$Map[$ErrorMapKey]] : $this->Error['simple'];
    }

    private function setError(object $ErrorObject)
    {
        $this->Error = [
            'simple' => $ErrorObject->errorInfo,
            'file' => $ErrorObject->getFile(),
            'line' => $ErrorObject->getLine(),
            'trace' => $ErrorObject->getTrace(),
            'traceString' => $ErrorObject->getTraceAsString()
        ];
    }
}