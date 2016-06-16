<?php

namespace HelloFresh\Mailer\Implementation\Common;

use Doctrine\Common\Collections\ArrayCollection;
use HelloFresh\Mailer\Contract\CollectionInterface;
use HelloFresh\Mailer\Contract\EquatableInterface;

class Collection extends ArrayCollection implements CollectionInterface
{
    /**
     * {@inheritdoc}
     */
    public function equals(EquatableInterface $object)
    {
        if (!($object instanceof self)) {
            return false;
        }

        foreach ($this as $element) {
            if (!$object->contains($element)) {
                return false;
            }
        }

        foreach ($object as $element) {
            if (!$this->contains($element)) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function contains($element)
    {
        return $this->indexOf($element) !== false;
    }

    /**
     * {@inheritDoc}
     */
    public function indexOf($element)
    {
        if (!($element instanceof EquatableInterface)) {
            return parent::indexOf($element);
        }

        foreach ($this as $index => $ownElement) {
            if ($element->equals($ownElement)) {
                return $index;
            }
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function removeElement($element)
    {
        $index = $this->indexOf($element);
        if ($index !== false) {
            return $this->remove($index);
        }
    }
}
