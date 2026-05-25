# API REST para App de Pacientes

Base URL local:

```text
http://localhost/OralCenter/api
```

Todas las respuestas son JSON. Los endpoints privados usan:

```http
Authorization: Bearer {access_token}
Content-Type: application/json
```

## Autenticacion

### Registrar paciente

```http
POST /paciente/register
```

```json
{
  "nombres": "Juan",
  "apellidos": "Perez",
  "dni": "12345678",
  "fecha_nacimiento": "1990-05-10",
  "telefono": "999999999",
  "email": "juan@mail.com",
  "direccion": "Av. Peru 123",
  "sexo": "M",
  "device_name": "Android"
}
```

### Login

```http
POST /paciente/login
```

```json
{
  "dni": "12345678",
  "fecha_nacimiento": "1990-05-10",
  "device_name": "Android"
}
```

Devuelve `access_token` para usar como Bearer token.

### Logout

```http
POST /paciente/logout
```

## Perfil

```http
GET /paciente/perfil
PUT /paciente/perfil
```

Campos editables:

```json
{
  "telefono": "999999999",
  "email": "juan@mail.com",
  "direccion": "Av. Peru 123",
  "alergia": "Ninguna",
  "observacion": "Paciente app"
}
```

## Catalogos

```http
GET /especialidades
GET /medicos
GET /medicos?especialidad=4
```

## Disponibilidad

```http
GET /disponibilidad?fecha=2026-05-17&medico=15&especialidad=4
```

Devuelve horas de 15 minutos con `disponible: true|false`.

## Citas

### Listar citas

```http
GET /citas
GET /citas?scope=historial
```

### Solicitar cita

```http
POST /citas
```

```json
{
  "fecha": "2026-05-17",
  "hora": "09:30",
  "medico": 15,
  "especialidad": 4,
  "motivo": "Dolor dental",
  "observacion": "Solicitado desde app"
}
```

La cita queda con estado `Pendiente`.

### Reprogramar cita

```http
PUT /citas/{id}
```

```json
{
  "fecha": "2026-05-18",
  "hora": "10:00",
  "medico": 15,
  "especialidad": 4,
  "motivo": "Dolor dental"
}
```

### Cancelar cita

```http
POST /citas/{id}/cancelar
```
