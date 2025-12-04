import { useState, useEffect } from 'react'

function ListaClientes() {
  const [clientes, setClientes] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState(null)

  useEffect(() => {
    cargarClientes()
  }, [])

  const cargarClientes = async () => {
    try {
      const response = await fetch('http://localhost:8000/api/personas', {
        headers: {
          'Accept': 'application/json',
        }
      })

      const data = await response.json()

      if (data.success) {
        setClientes(data.data.data || [])
      } else {
        setError('Error al cargar clientes')
      }
    } catch (err) {
      setError('Error de conexión')
    } finally {
      setLoading(false)
    }
  }

  if (loading) {
    return (
      <div className="card h-100">
        <div className="card-body text-center">
          <div className="spinner-border text-primary" role="status">
            <span className="visually-hidden">Cargando...</span>
          </div>
          <p className="mt-3 text-muted">Cargando clientes...</p>
        </div>
      </div>
    )
  }

  if (error) {
    return (
      <div className="card h-100">
        <div className="card-body">
          <div className="alert alert-danger">
            {error}
          </div>
        </div>
      </div>
    )
  }

  return (
    <div className="card h-100">
      <div className="card-body">
        <h5 className="card-title mb-4">👥 Clientes Registrados</h5>

        {clientes.length === 0 ? (
          <p className="text-center text-muted">
            No hay clientes registrados aún.
          </p>
        ) : (
          <div className="table-responsive">
            <table className="table table-sm table-hover">
              <thead className="table-light">
                <tr>
                  <th>Cédula</th>
                  <th>Nombre</th>
                  <th>Email</th>
                </tr>
              </thead>
              <tbody>
                {clientes.map((cliente) => (
                  <tr key={cliente.id}>
                    <td className="fw-semibold">{cliente.cedula}</td>
                    <td>{cliente.nombre}</td>
                    <td className="text-muted small">{cliente.email}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}

        <div className="mt-3">
          <button 
            className="btn btn-sm btn-outline-primary"
            onClick={cargarClientes}
          >
            Actualizar
          </button>
          <span className="ms-2 text-muted small">
            {clientes.length} cliente{clientes.length !== 1 ? 's' : ''}
          </span>
        </div>
      </div>
    </div>
  )
}
export default ListaClientes