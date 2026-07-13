import React from 'react';
type Props = {
    attributes: object;
    setAttributes: () => void;
}
export default function Edit( { attributes, setAttributes }: Props ) {
    return (
        <p>
            {'CPJ Appointment Scheduler – hello from the editor!'}
        </p>
    );
}