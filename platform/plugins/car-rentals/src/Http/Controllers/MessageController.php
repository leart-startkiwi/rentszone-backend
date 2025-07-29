<?php

namespace Botble\CarRentals\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\CarRentals\Forms\MessageForm;
use Botble\CarRentals\Http\Requests\MessageRequest;
use Botble\CarRentals\Models\Message;
use Botble\CarRentals\Tables\MessageTable;

class MessageController extends BaseController
{
    public function __construct()
    {
        $this
            ->breadcrumb()
            ->add(trans('plugins/car-rentals::message.name'), route('car-rentals.message.index'));
    }

    public function index(MessageTable $table)
    {
        $this->pageTitle(trans('plugins/car-rentals::message.name'));

        return $table->renderTable();
    }

    public function edit(Message $message)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $message->name]));

        return MessageForm::createFromModel($message)->renderForm();
    }

    public function update(Message $message, MessageRequest $request)
    {
        MessageForm::createFromModel($message)
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.message.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Message $message): DeleteResourceAction
    {
        return DeleteResourceAction::make($message);
    }
}
