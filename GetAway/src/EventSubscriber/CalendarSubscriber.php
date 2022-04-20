<?php
namespace App\EventSubscriber;
use App\Repository\BookingRepository;
use App\Repository\ReservationRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
class CalendarSubscriber   extends AbstractController implements EventSubscriberInterface
{
    private $bookingRepository;
    private $router;

    public function __construct(
        ReservationRepository $bookingRepository,
        UrlGeneratorInterface $router
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }
    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        // Modify the query to fit to your entity and needs
        // Change booking.beginAt by your start date property
            dump($this->getUser()->getUsername());
        $bookings = $this->bookingRepository
            ->createQueryBuilder('r')
            ->join('r.idClient','c')
            ->Where('c.id=:id')
            ->setParameter('id',$this->getUser()->getUsername())
            ->getQuery()
            ->getResult()
        ;
   dump($bookings);
        foreach ($bookings as $booking) {
            // this create the events with your data (here booking data) to fill calendar
            $bookingEvent = new Event(
                "R:".''.$booking->getType(),
                $booking->getDateDebut(),
                $booking->getDateFin()
            );


            $bookingEvent->setOptions([
                'backgroundColor' => 'green',
                'borderColor' => 'green',
                'textColor'=>'white',
                'list-item'=>'blue'
            ]);
            $bookingEvent->addOption(
                'url',
                $this->router->generate('booking_calendar', [
                    'id' => $booking->getId(),
                ])
            );

            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($bookingEvent);
        }
    }}
