<?php

namespace Apie\CommonValueObjects;

enum Stars: string
{
    case Zero = '☆☆☆☆☆';
    case One = '★☆☆☆☆';

    case Two = '★★☆☆☆';

    case Three = '★★★☆☆';

    case Four = '★★★★☆';

    case Five = '★★★★★';
}
