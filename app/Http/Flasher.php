<?php

namespace App\Http;

class Flasher
{

    /**
     * Generate a flash message and add it to Session Storage.
     *
     * @param string $title
     * @param string $message
     * @param string $level (One of: 'success', 'info', 'warning', 'error')
     * @param string $type (One of: 'bs_flash', 'bs_dismiss', 'bs_timed')
     * @return mixed
     */
    public function flashMessage($message, $title, $level, $type)
    {
        return session()->flash($type, [
            'title'   => $title,
            'message' => $message,
            'level'   => $level
        ]);
    }

    /**
     * Default Alert Message NOT dismissable.
     *
     * @param $message
     * @param string $title
     * @return mixed
     */
    public function message($message, $title = 'Alert')
    {
        return $this->flashMessage($message, $title, 'info', 'bs_flash');
    }

    /**
     * Default DISMISSABLE Alert Message.
     *
     * @param $message
     * @param string $title
     * @return mixed
     */
    public function messageDismiss($message, $title = 'Alert')
    {
        return $this->flashMessage($message, $title, 'info', 'bs_dismiss');
    }

    /**
     * Return an 'INFO' level Bootstrap Alert style message to the session.
     *
     * @param $title
     * @param $message
     * @return mixed
     */
    public function bsInfo($message, $title = 'FYI')
    {
        return $this->flashMessage($message, $title, 'info', 'bs_flash');
    }

    /**
     * Return a DISMISSABLE 'INFO' level Bootstrap Alert style message to the session.
     *
     * @param $title
     * @param $message
     * @return mixed
     */
    public function bsInfoDismiss($message, $title = 'FYI')
    {
        return $this->flashMessage($message, $title, 'info', 'bs_dismiss');
    }

    /**
     * Return a 'SUCCESS' level Bootstrap Alert style message to the session.
     *
     * @param $title
     * @param $message
     * @return mixed
     */
    public function bsSuccess($message, $title = 'Success!')
    {
        return $this->flashMessage($message, $title, 'success', 'bs_flash');
    }

    /**
     * Return a DISMISSABLE 'SUCCESS' level Bootstrap Alert style message to the session.
     *
     * @param $title
     * @param $message
     * @return mixed
     */
    public function bsSuccessDismiss($message, $title = 'Success!')
    {
        return $this->flashMessage($message, $title, 'success', 'bs_dismiss');
    }

    /**
     * Return a 'WARNING' level Bootstrap Alert style message to the session.
     *
     * @param $title
     * @param $message
     * @return mixed
     */
    public function bsWarning($message, $title = 'Warning!')
    {
        return $this->flashMessage($message, $title, 'warning', 'bs_flash');
    }

    /**
     * Return a DISMISSABLE 'WARNING' level Bootstrap Alert style message to the session.
     *
     * @param $title
     * @param $message
     * @return mixed
     */
    public function bsWarningDismiss($message, $title = 'Warning!')
    {
        return $this->flashMessage($message, $title, 'warning', 'bs_dismiss');
    }

    /**
     * Return an 'ERROR' level Bootstrap Alert style message to the session.
     *
     * @param $title
     * @param $message
     * @return mixed
     */
    public function bsError($message, $title = 'Error!')
    {
        return $this->flashMessage($message, $title, 'danger', 'bs_flash');
    }

    /**
     * Return a DISMISSABLE 'ERROR' level Bootstrap Alert style message to the session.
     *
     * @param $title
     * @param $message
     * @return mixed
     */
    public function bsErrorDismiss($message, $title = 'Error!')
    {
        return $this->flashMessage($message, $title, 'danger', 'bs_dismiss');
    }
}