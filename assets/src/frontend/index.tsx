import domReady from '@wordpress/dom-ready';
import { createRoot } from 'react-dom/client';
import apiFetch from '@wordpress/api-fetch';
import FrontEndApp from './FrontEndApp';
import React from 'react';

domReady( () => {
    const rootEl = document.getElementById( 'cpj-appt-sched-block-root' );

    if ( !rootEl ) {
        return;
    }
    // @ts-ignore
    apiFetch.use( apiFetch.createNonceMiddleware( window?.cpjApptSchedAdminScript?.nonce ) );

    createRoot( rootEl ).render( <FrontEndApp/> );
} );