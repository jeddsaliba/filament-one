<?php

namespace App\Livewire;

use Filament\Facades\Filament;
use Filament\Forms;
use Jeffgreco13\FilamentBreezy\Livewire\PersonalInfo as LivewirePersonalInfo;

class PersonalInfo extends LivewirePersonalInfo
{
    public array $only = ['name', 'email', 'phone', 'birthdate'];

    /**
     * @inheritDoc
     */
    /**
     * Gets the form schema for the Livewire component.
     *
     * @param Forms\Form $form The form instance.
     *
     * @return Forms\Form The form schema.
     */
    public function form(Forms\Form $form): Forms\Form
    {
        $groupFields = Forms\Components\Group::make([
            $this->getNameComponent(),
            $this->getEmailComponent(),
            \Ysfkaya\FilamentPhoneInput\Forms\PhoneInput::make('phone')
                ->formatStateUsing(fn () => $this->user->userProfile?->phone),
            Forms\Components\DatePicker::make('birthdate')
                ->formatStateUsing(fn () => $this->user->userProfile?->birthdate->format('Y-m-d')),
        ])->columnSpan(2);
        return $form
            ->schema(($this->hasAvatars)
            ? [filament('filament-breezy')->getAvatarUploadComponent()->alignCenter(), $groupFields]
            : [$groupFields])->statePath('data');
    }

    /**
     * Saves the user and user profile with the updated data and sends a notification if the user is logged in.
     * 
     * @return void
     */
    public function submit(): void
    {
        $data = collect($this->form->getState())->only($this->only)->all();
        $this->user->update($data);
        $this->user->userProfile->update($data);
        $this->sendNotification();
    }
}
