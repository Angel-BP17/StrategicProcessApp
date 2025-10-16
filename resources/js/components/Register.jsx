import React, { useState } from 'react';

export default function Register() {
const [name, setName] = useState('');
const [email, setEmail] = useState('');
const [password, setPassword] = useState('');
const [msg, setMsg] = useState(null);

const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

async function handleSubmit(e) {
    e.preventDefault();
    const res = await fetch('/register', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ name, email, password }),
    });
    if (res.ok) {
        window.location.href = '/';
    } else {
        const data = await res.json().catch(() => ({}));
        setMsg(data.message || 'Registration failed');
    }
}

return (
    <div className="p-6">
        <h2 className="text-2xl mb-4">Register</h2>
        {msg && <div className="mb-4 text-red-400">{msg}</div>}
        <form onSubmit={handleSubmit} className="space-y-3">
            <div>
                <input value={name} onChange={e => setName(e.target.value)} placeholder="Nombre" className="p-2 rounded w-full" />
            </div>
            <div>
                <input value={email} onChange={e => setEmail(e.target.value)} placeholder="Email" className="p-2 rounded w-full" />
            </div>
            <div>
                <input value={password} onChange={e => setPassword(e.target.value)} type="password" placeholder="Password" className="p-2 rounded w-full" />
            </div>
            <button className="px-4 py-2 bg-green-500 text-white rounded">Crear cuenta</button>
        </form>
    </div>
);
}