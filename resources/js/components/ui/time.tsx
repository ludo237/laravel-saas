import {formatDistanceToNow} from 'date-fns';
import {it} from 'date-fns/locale/it';
import type {FC} from 'react';

const Time: FC<{date: Date}> = ({date}) => {
  return (
    <time dateTime={date.toString()}>
      {formatDistanceToNow(date, {locale: it, addSuffix: true})}
    </time>
  );
};

export {Time};
