import domReady from '@wordpress/dom-ready';
import { createRoot } from 'react-dom/client';
import apiFetch from '@wordpress/api-fetch';
import App from './App';
import React from 'react';

domReady( () => {
    const rootEl = document.getElementById( 'cpj-cal-sched-root' );

    if ( !rootEl ) {
        return;
    }

    apiFetch.use( apiFetch.createNonceMiddleware( window.cpjCalSchedAdminScript.nonce ) );

    createRoot( rootEl ).render( <App/> );
} );