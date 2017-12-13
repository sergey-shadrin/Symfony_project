<?php

class registrationAction extends sfAction
{
    private const DEFAULT_ROLE = UserRole::USER;

    public function execute($request)
    {
        $this->registrationForm = new UserForm();
        if ($request->isMethod(sfRequest::POST))
        {
            $this->processForm($request);
        }
    }

    protected function processForm(sfWebRequest $request)
    {
        $userParameter = $request->getParameter($this->registrationForm->getName());
        $this->registrationForm->bind($userParameter);
        if ($this->registrationForm->isValid())
        {
            $login = $userParameter[UserForm::LOGIN];
            $password = $userParameter[UserForm::LOGIN];
            $fisrtName = $userParameter[UserForm::FIRST_NAME];
            $lastName = $userParameter[UserForm::LOGIN];
            UserPeer::createUser($login, $password, registrationAction::DEFAULT_ROLE, $fisrtName, $lastName);
            $this->redirect('@log_in');
        }
    }
}