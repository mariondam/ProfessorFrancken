<?php

namespace Tests\Francken\Domain\Members\Registration;

use Broadway\EventSourcing\Testing\AggregateRootScenarioTestCase;
use Francken\Domain\Members\Registration\RegistrationRequest;
use Francken\Domain\Members\Registration\RegistrationRequestId;
use Francken\Domain\Members\Registration\Events\RegistrationRequestSubmitted;
use Francken\Domain\Members\Person;
use Francken\Domain\Members\ContactInfo;
use Francken\Domain\Members\Email;
use Francken\Domain\Members\Address;
use DateTimeImmutable;

class RegistrationRequestTest extends AggregateRootScenarioTestCase
{
    protected function getAggregateRootClass()
    {
        return RegistrationRequest::class;
    }

    /** @test */
    public function a_visitor_can_request_to_be_registered()
    {
        $id = RegistrationRequestId::generate();

        $this->scenario
            ->when(function () use ($id) {
                return RegistrationRequest::submit(
                    $id,
                    Person::describe(
                        'Mark',
                        '',
                        'Redeman', // surname
                        Person::MALE,
                        new DateTimeImmutable('1993-04-26'),
                        ContactInfo::describe(
                            new Email('markredeman@gmail.com'),
                            new Address(
                                'Groningen',
                                '9742GS',
                                'Plutolaan',
                                '11'
                            )
                        )
                    ),
                    's2218356',
                    'Msc Applied Mathematics'
                    // note: could add an additional "comment" section where a foreigner could tell the board that he/she lives outside of the Netherlands
                );
            })
            ->then([
                new RegistrationRequestSubmitted(
                    $id,
                    Person::describe(
                        'Mark',
                        '',
                        'Redeman', // surname
                        Person::MALE,
                        new DateTimeImmutable('1993-04-26'),
                        ContactInfo::describe(
                            new Email('markredeman@gmail.com'),
                            new Address(
                                'Groningen',
                                '9742GS',
                                'Plutolaan',
                                '11'
                            )
                        )
                    ),
                    's2218356',
                    'Msc Applied Mathematics'
                )
            ]);
    }


    // a potential member can be interested in doing committee work
}