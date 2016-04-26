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
     * @param string $message
     * @param string $title
     * @return mixed
     */
    public function message($message, $title = 'Alert')
    {
        return $this->flashMessage($message, $title, 'info', 'vue_flash');
    }

    /**
     * Default DISMISSABLE Alert Message.
     *
     * @param string $message
     * @param string $title
     * @return mixed
     */
    public function messageDismiss($message, $title = 'Alert')
    {
        return $this->flashMessage($message, $title, 'info', 'vue_dismiss');
    }

    /**
     * Default TIMED Alert Message.
     *
     * @param string $message
     * @param string $title
     * @return mixed
     */
    public function messageTimed($message, $title = 'Alert')
    {
        return $this->flashMessage($message, $title, 'info', 'vue_timed');
    }

    /**
     * Return an 'INFO' level VueStrap style alert message to the session.
     *
     * @param string $title - has default
     * @param string $message
     * @return mixed
     */
    public function vueInfo($message, $title = 'FYI')
    {
        return $this->flashMessage($message, $title, 'info', 'vue_flash');
    }

    /**
     * Return a DISMISSABLE 'INFO' level VueStrap style alert message to the session.
     *
     * @param string $title - has default
     * @param string $message
     * @return mixed
     */
    public function vueInfoDismiss($message, $title = 'FYI')
    {
        return $this->flashMessage($message, $title, 'info', 'vue_dismiss');
    }

    /**
     * Return a TIMED 'INFO' level VueStrap style alert message to the session.
     *
     * @param string $title - has default
     * @param string $message
     * @return mixed
     */
    public function vueInfoTimed($message, $title = 'FYI')
    {
        return $this->flashMessage($message, $title, 'info', 'vue_timed');
    }

    /**
     * Return a 'SUCCESS' level VueStrap style alert message to the session.
     *
     * @param string $title - has default
     * @param string $message
     * @return mixed
     */
    public function vueSuccess($message, $title = 'Success!')
    {
        return $this->flashMessage($message, $title, 'success', 'vue_flash');
    }

    /**
     * Return a DISMISSABLE 'SUCCESS' level VueStrap style alert message to the session.
     *
     * @param string $title - has default
     * @param string $message
     * @return mixed
     */
    public function vueSuccessDismiss($message, $title = 'Success!')
    {
        return $this->flashMessage($message, $title, 'success', 'vue_dismiss');
    }

    /**
     * Return a TIMED 'SUCCESS' level VueStrap style alert message to the session.
     *
     * @param string $title - has default
     * @param string $message
     * @return mixed
     */
    public function vueSuccessTimed($message, $title = 'Success!')
    {
        return $this->flashMessage($message, $title, 'success', 'vue_timed');
    }

    /**
     * Return a 'WARNING' level VueStrap style alert message to the session.
     *
     * @param string $title - has default
     * @param string $message
     * @return mixed
     */
    public function vueWarning($message, $title = 'Warning!')
    {
        return $this->flashMessage($message, $title, 'warning', 'vue_flash');
    }

    /**
     * Return a DISMISSABLE 'WARNING' level VueStrap style alert message to the session.
     *
     * @param string $title - has default
     * @param string $message
     * @return mixed
     */
    public function vueWarningDismiss($message, $title = 'Warning!')
    {
        return $this->flashMessage($message, $title, 'warning', 'vue_dismiss');
    }

    /**
     * Return a TIMED 'WARNING' level VueStrap style alert message to the session.
     *
     * @param string $title - has default
     * @param string $message
     * @return mixed
     */
    public function vueWarningTimed($message, $title = 'Warning!')
    {
        return $this->flashMessage($message, $title, 'warning', 'vue_timed');
    }

    /**
     * Return an 'ERROR' level VueStrap style alert message to the session.
     *
     * @param string $title - has default
     * @param string $message
     * @return mixed
     */
    public function vueError($message, $title = 'Error!')
    {
        return $this->flashMessage($message, $title, 'danger', 'vue_flash');
    }

    /**
     * Return a DISMISSABLE 'ERROR' level VueStrap style alert message to the session.
     *
     * @param string $title - has default
     * @param string $message
     * @return mixed
     */
    public function vueErrorDismiss($message, $title = 'Error!')
    {
        return $this->flashMessage($message, $title, 'danger', 'vue_dismiss');
    }

    /**
     * Return a TIMED 'ERROR' level VueStrap style alert message to the session.
     *
     * @param string $title - has default
     * @param string $message
     * @return mixed
     */
    public function vueErrorTimed($message, $title = 'Error!')
    {
        return $this->flashMessage($message, $title, 'danger', 'vue_timed');
    }
}