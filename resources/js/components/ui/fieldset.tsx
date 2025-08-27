import type {FieldsetHTMLAttributes} from 'react';

const Fieldset: React.FC<FieldsetHTMLAttributes<HTMLElement>> = (props) => {
  return <fieldset className={props.className}>{props.children}</fieldset>;
};

export {Fieldset};
