import domReady from '@wordpress/dom-ready';
import { createRoot } from 'react-dom/client';
import apiFetch from '@wordpress/api-fetch';
import AdminApp from './AdminApp';
import React from 'react';

type CPJApptSchedAdminScript = {
    nonce: string;
    restURL: string;
}

domReady( () => {
    const rootEl = document.getElementById( 'cpj-appt-sched-settings-root' );

    if ( !rootEl ) {
        return;
    }
    // @ts-ignore
    apiFetch.use( apiFetch.createNonceMiddleware( window?.cpjApptSchedAdminScript?.nonce ) );

    createRoot( rootEl ).render( <SettingsApp/> );
} );