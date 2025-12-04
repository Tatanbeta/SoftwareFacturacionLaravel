import { useState } from 'react'
import CrearCliente from './components/CrearCliente'
import ListaClientes from './components/ListaClientes'

function App() {
  const [actualizar, setActualizar] = useState(0)

  const handleClienteCreado = () => {
    // Actualizar la lista cuando se crea un cliente
    setActualizar(prev => prev + 1)
  }

  return (
    <div className="container">
      <div className="row mb-4">
        <div className="col-12">
          <div className="demo-badge">
            DEMO REACT - Consumiendo API Laravel
          </div>
          <h1 className="text-white mb-3">Sistema de Facturación</h1>
          <p className="text-white-50">
            Este es un ejemplo de React consumiendo la API RESTful de Laravel.
          </p>
        </div>
      </div>

      <div className="row">
        <div className="col-md-6 mb-4">
          <CrearCliente onClienteCreado={handleClienteCreado} />
        </div>
        
        <div className="col-md-6 mb-4">
          <ListaClientes key={actualizar} />
        </div>
      </div>

      <div className="row">
        <div className="col-12">
          <div className="card">
            <div className="card-body">
              <h5 className="card-title">Información Técnica</h5>
              <ul className="mb-0">
                <li><strong>Frontend:</strong> React 18 + Vite</li>
                <li><strong>Backend:</strong> Laravel API (http://localhost:8000/api)</li>
                <li><strong>Estilos:</strong> Bootstrap 5</li>
                <li><strong>Comunicación:</strong> Fetch API (REST)</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}
export default App