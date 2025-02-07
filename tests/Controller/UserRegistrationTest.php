<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRegistrationTest extends WebTestCase
{
    public function testRegistrationPageIsAccessible(): void
    {
        $client = static::createClient();

        // Envoi d'une requête GET vers la page d'inscription
        $crawler = $client->request('GET', '/inscription');

        // Vérifier que la réponse HTTP est réussie (code 200)
        $this->assertResponseIsSuccessful();

        // Vérifier que le formulaire est présent dans la page
        $this->assertSelectorExists('form');

        // Vérifier la présence des champs du formulaire
        $this->assertSelectorExists('input[name="registration_form[firstname]"]');
        $this->assertSelectorExists('input[name="registration_form[lastname]"]');
        $this->assertSelectorExists('input[name="registration_form[email]"]');
        $this->assertSelectorExists('input[name="registration_form[plainPassword]"]');
        $this->assertSelectorExists('input[name="registration_form[agreeTerms]"]');

        // Vérifier que le bouton de soumission contient le texte "Créer mon compte"
        $this->assertSelectorTextContains('button[type="submit"]', 'Créer mon compte');

        // Optionnel : vérifier le titre de la page
        $this->assertSelectorTextContains('title', 'KoulMaghreb - Inscription');

    }
}
