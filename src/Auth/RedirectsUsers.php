<?php

namespace LiveCMS\Auth;

trait RedirectsUsers
{
    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectUserIndex()
    {
        return redirect()->intended(LC_Route('index'));
    }
}
