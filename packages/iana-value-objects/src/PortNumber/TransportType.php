<?php
namespace Apie\IanaValueObjects\PortNumber;

enum TransportType: string
{
    case Tcp = 'tcp';
    case Udp = 'udp';
    case Sctp = 'sctp';
    case Dccp = 'dccp';
}
