<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\Capability;

trait Notify
{
    /**
     * @var \SplObjectStorage|\SplObserver[]
     */
    private $observers;

    /**
     * @param \SplObserver $observer
     */
    public function attach(\SplObserver $observer)
    {
        $this->getObservers()
            ->attach($observer);
    }

    /**
     * @param \SplObserver $observer
     */
    public function detach(\SplObserver $observer)
    {
        $this->getObservers()
            ->detach($observer);
    }

    /**
     * Classes using this trait must remember to implement \SplSubject!
     */
    public function notify()
    {
        foreach ($this->getObservers() as $observer) {
            $observer->update($this);
        }
    }

    /**
     * @return \SplObjectStorage|\SplObserver[]
     */
    private function getObservers()
    {
        if (!isset($this->observers)) {
            $this->observers = new \SplObjectStorage();
        }

        return $this->observers;
    }
}