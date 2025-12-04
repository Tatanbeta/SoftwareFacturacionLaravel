import { useState } from 'react'

function CrearCliente({ onClienteCreado }) {
  const [cedula, setCedula] = useState('')
  const [nombre, setNombre] = useState('')
  const [email, setEmail] = useState('')
  const [loading, setLoading] = useState(false)
  const [mensaje, setMensaje] = useState(null)
  const [errores, setErrores] = useState({})

  const handleSubmit = async (e) => {
    e.preventDefault()
    setLoading(true)
    setMensaje(null)
    setErrores({})

    try {
      const response = await fetch('http://localhost:8000/api/personas', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          cedula,
          nombre,
          email
        })
      })

      const data = await response.json()

      if (response.ok && data.success) {
        setMensaje({
          tipo: 'success',
          texto: 'Cliente creado exitosamente'
        })
        //reset formulario
        setCedula('')
        setNombre('')
        setEmail('')

        if (onClienteCreado) {
          onClienteCreado()
        }
      } else {
        if (data.errors) {
          setErrores(data.errors)
        }
        setMensaje({
          tipo: 'danger',
          texto: data.message || 'Error al crear cliente'
        })
      }
    } catch (error) {
      setMensaje({
        tipo: 'danger',
        texto: 'Error de conexión con el servidor'
      })
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="card h-100">
      <div className="card-body">
        <h5 className="card-title mb-4">Crear Nuevo Cliente</h5>

        {mensaje && (
          <div className={`alert alert-${mensaje.tipo} alert-dismissible fade show`} role="alert">
            {mensaje.texto}
            <button 
              type="button" 
              className="btn-close" 
              onClick={() => setMensaje(null)}
            ></button>
          </div>
        )}

        <form onSubmit={handleSubmit}>
          <div className="mb-3">
            <label className="form-label fw-semibold">
              Cédula <span className="text-danger">*</span>
            </label>
            <input
              type="text"
              className={`form-control ${errores.cedula ? 'is-invalid' : ''}`}
              value={cedula}
              onChange={(e) => setCedula(e.target.value)}
              placeholder="Ingrese la cédula"
              required
              disabled={loading}
            />
            {errores.cedula && (
              <div className="invalid-feedback">
                {errores.cedula[0]}
              </div>
            )}
          </div>

          <div className="mb-3">
            <label className="form-label fw-semibold">
              Nombre Completo <span className="text-danger">*</span>
            </label>
            <input
              type="text"
              className={`form-control ${errores.nombre ? 'is-invalid' : ''}`}
              value={nombre}
              onChange={(e) => setNombre(e.target.value)}
              placeholder="Nombre Completo"
              required
              disabled={loading}
            />
            {errores.nombre && (
              <div className="invalid-feedback">
                {errores.nombre[0]}
              </div>
            )}
          </div>

          <div className="mb-3">
            <label className="form-label fw-semibold">
              Correo Electrónico <span className="text-danger">*</span>
            </label>
            <input
              type="email"
              className={`form-control ${errores.email ? 'is-invalid' : ''}`}
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              placeholder="Correo Electrónico"
              required
              disabled={loading}
            />
            {errores.email && (
              <div className="invalid-feedback">
                {errores.email[0]}
              </div>
            )}
          </div>

          <button 
            type="submit" 
            className="btn btn-success w-100"
            disabled={loading}
          >
            {loading ? (
              <>
                <span className="spinner-border spinner-border-sm me-2"></span>
                Creando...
              </>
            ) : (
              <>
                <strong>✓</strong> Crear Cliente
              </>
            )}
          </button>
        </form>
      </div>
    </div>
  )
}
export default CrearCliente