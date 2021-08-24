<?php
trait ResetMocks
{
    protected function resetMockObjects()
    {
        $refl = new ReflectionObject($this);
        while (!$refl->hasProperty('mockObjects')) {
            $refl = $refl->getParentClass();
        }
        $prop = $refl->getProperty('mockObjects');
        $prop->setAccessible(true);
        $prop->setValue($this, array());
    }
}