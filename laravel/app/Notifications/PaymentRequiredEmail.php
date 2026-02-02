<?php

namespace App\Notifications;

use App\Models\Offer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentRequiredEmail extends Notification implements ShouldQueue
{
    use Queueable;

    public $offer;
    public $paymentType; // 'requested' o 'offered'
    public $emailMessage; // Cambié el nombre para evitar conflicto

    public function __construct(Offer $offer, $paymentType)
    {
        $this->offer = $offer;
        $this->paymentType = $paymentType;
        
        // Definir el mensaje aquí en lugar de en toMail
        if ($this->paymentType === 'requested') {
            $this->emailMessage = "El ofertante ha pagado su comisión. Ahora debes pagar la comisión por tu producto \"{$offer->productRequested->title}\" para completar la permuta.";
        } else {
            $this->emailMessage = "El dueño del producto ha pagado su comisión. Ahora debes pagar la comisión por tus productos ofrecidos para completar la permuta.";
        }
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $offer = $this->offer->load(['productRequested', 'productsOffered', 'fromUser', 'toUser']);

        return (new MailMessage)
            ->subject('Pago de comisión requerido - Permuta2')
            ->view('emails.payment_required', [
                'offer' => $offer,
                'user' => $notifiable,
                'emailMessage' => $this->emailMessage, // Cambiado a emailMessage
                'paymentType' => $this->paymentType
            ]);
    }
}