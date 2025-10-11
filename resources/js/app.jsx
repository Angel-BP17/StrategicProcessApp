import './bootstrap';
import '../css/app.css';

import React from 'react';
import { createRoot } from 'react-dom/client';

function App() {
    return (
        <div>
            <h1>Laravel + React App</h1>
        </div>
    );
}

const container = document.getElementById('app');
const root = createRoot(container);
root.render(<App />);