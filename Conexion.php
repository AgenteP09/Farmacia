
<?php

class Conexion{
    private $servidor="127.0.0.1";
    private $base="farmacia";
    private $usuario="root";
    private $pass= "Farmacia2026#";
    public $enlace=null;

public function contarClientes($condicion = null) {
    if ($this->enlace == null) {
        $this->conectar();
    }
    $condicion = $condicion != null ? " WHERE " . $condicion : "";
    $consulta = mysqli_query($this->enlace, "SELECT COUNT(*) as total FROM clientes $condicion");
    
    if ($consulta && $fila = mysqli_fetch_assoc($consulta)) {
        return $fila['total'];
    }
    return 0;
}

public function contarProductos($condicion = null) {
    if ($this->enlace == null) {
        $this->conectar();
    }
    $condicion = $condicion != null ? " WHERE " . $condicion : "";
    $consulta = mysqli_query($this->enlace, "SELECT COUNT(*) as total FROM productos_generales $condicion");
    
    if ($consulta && $fila = mysqli_fetch_assoc($consulta)) {
        return $fila['total'];
    }
    return 0;
}

public function contarMedicamentos($condicion = null){
    if($this->enlace == null){
        $this->conectar();
    }
    $condicion = $condicion != null ? " WHERE " . $condicion : "";
    $consulta = mysqli_query($this->enlace, "SELECT COUNT(*) as total FROM medicamentos $condicion");
    
    if ($consulta && $fila = mysqli_fetch_assoc($consulta)) {
        return $fila['total'];
    }
    return 0;

}

    public function conectar(){
        $this->enlace = mysqli_connect($this->servidor,$this->usuario,$this->pass,$this->base);
        if(!$this->enlace){
            echo 'No se pudo conectar a la base de datos';

        }
    }


// inserta clientes
  public function insertarCliente($nombre, $apellido, $correo, $telefono, $direccion){
    if ($this->enlace == null) {
        $this->conectar();
    }

    $ins = 'INSERT INTO clientes (nombre, apellido, correo, telefono, direccion) VALUES (?, ?, ?, ?, ?)';
    $ins = $this->enlace->prepare($ins);

    if (!$ins) {
        // Mostramos el error si el prepare falla
        die("Error en prepare: " . $this->enlace->error);
    }

    $ins->bind_param('sssss', $nombre, $apellido, $correo, $telefono, $direccion);
    $res = $ins->execute();
    return $res > 0;
}

//actualiza clientes
public function actualizarCliente($id, $nombre, $apellido, $correo, $telefono, $direccion) {
    if ($this->enlace == null) {
        $this->conectar();
    }

    $sql = 'UPDATE clientes SET nombre = ?, apellido = ?, correo = ?, telefono = ?, direccion = ? WHERE id_cliente = ?';
    $stmt = $this->enlace->prepare($sql);

    if (!$stmt) {
        die("Error en prepare: " . $this->enlace->error);
    }

    $stmt->bind_param('sssssi', $nombre, $apellido, $correo, $telefono, $direccion, $id);
    $res = $stmt->execute();
    return $res > 0;
}

public function obtenerClientePorId($id) {
        if ($this->enlace === null) {
            $this->conectar();
        }

        $sql  = "SELECT * FROM clientes WHERE id_cliente = ?";
        $stmt = $this->enlace->prepare($sql);

        if (!$stmt) {
            die("Error en prepare (obtenerClientePorId): " . $this->enlace->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc(); // devuelve un array asociativo o null si no existe
    }

    //eliminar cliente
    public function eliminarCliente($id) {
    if ($this->enlace === null) {
        $this->conectar();
    }

    $sql = "DELETE FROM clientes WHERE id_cliente = ?";
    $stmt = $this->enlace->prepare($sql);

    if (!$stmt) {
        die("Error en prepare (eliminarCliente): " . $this->enlace->error);
    }

    $stmt->bind_param("i", $id);
    return $stmt->execute();
}



 public function eliminarProducto($id) {
    if ($this->enlace == null) {
        $this->conectar();  // Asegúrate que conectar() crea $this->enlace como mysqli
    }

    // Preparar consulta para obtener imagen
    $sqlImagen = "SELECT imagen FROM productos_generales WHERE id_producto = ?";
    $stmtImagen = $this->enlace->prepare($sqlImagen);

    if (!$stmtImagen) {
        throw new Exception("Error en prepare para SELECT imagen: " . $this->enlace->error);
    }

    $stmtImagen->bind_param("i", $id);
    $stmtImagen->execute();
    $resultado = $stmtImagen->get_result();
    $fila = $resultado->fetch_assoc();

    if ($fila && !empty($fila['imagen']) && file_exists($fila['imagen'])) {
        unlink($fila['imagen']);
    }

    // Preparar consulta para eliminar producto
    $sqlEliminar = "DELETE FROM productos_generales WHERE id_producto = ?";
    $stmtEliminar = $this->enlace->prepare($sqlEliminar);

    if (!$stmtEliminar) {
        throw new Exception("Error en prepare para DELETE: " . $this->enlace->error);
    }

    $stmtEliminar->bind_param("i", $id);
    return $stmtEliminar->execute();
} 


    
 

    public function consultarCliente($filtro = null) {
    if ($this->enlace === null) {
        $this->conectar();
    }

    if ($filtro !== null) {
        $filtroEscapado = $this->enlace->real_escape_string($filtro);
        $condicion = "WHERE nombre LIKE '%{$filtroEscapado}%'";
    } else {
        $condicion = "";
    }

    $sql = "SELECT * FROM clientes $condicion ORDER BY id_cliente DESC";
    return $this->enlace->query($sql);
}


// Productos generales
public function insertarProductoGeneral($nombre, $descripcion, $precio, $stock, $tipo, $id_categoria, $id_proveedor, $imagen) {
    if ($this->enlace == null) {
        $this->conectar();
    }

    $sql = "INSERT INTO productos_generales (nombre, descripcion, precio, stock, tipo, id_categoria, id_proveedor, imagen)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $this->enlace->prepare($sql);

    if (!$stmt) {
        die("Error en prepare: " . $this->enlace->error);
    }

    $stmt->bind_param("ssdisiss", $nombre, $descripcion, $precio, $stock, $tipo, $id_categoria, $id_proveedor, $imagen);

   $res = $stmt->execute();

if (!$res) {
    echo "Error al ejecutar: " . $stmt->error;
}

return $res;

}

public function consultarProductosGenerales($condicion = null) {
    if ($this->enlace == null) {
        $this->conectar();
    }

    $condicion = $condicion != null ? " WHERE " . $condicion : "";
    $consulta = mysqli_query($this->enlace, "SELECT * FROM productos_generales $condicion");
    return $consulta;
}

public function actualizarProducto($id_producto, $nombre, $descripcion, $precio, $stock, $tipo, $id_categoria, $id_proveedor, $imagen) {
    if ($this->enlace == null) {
        $this->conectar();
    }

    $sql = " UPDATE productos_generales SET nombre=?, descripcion=?, precio=?, stock=?, tipo=?, id_categoria= ?, 
                id_proveedor= ?, 
                imagen=? WHERE id_producto = ? ";

    $stmt = $this->enlace->prepare($sql);
    if (!$stmt) {
        die("Error en prepare: " . $this->enlace->error);
    }

    $stmt->bind_param("ssdisissi",$nombre,$descripcion,$precio,$stock,$tipo,$id_categoria,$id_proveedor,$imagen,$id_producto);

    $res = $stmt->execute();
    if (!$res) {
        echo "Error al ejecutar: " . $stmt->error;
    }

    $stmt->close();
    return $res;
}

public function insertarMedicamento($nombreComercial, $nombreGenerico, $fecha_caducidad, $requiere_receta, $precio, $stock, $imagen) {
    if ($this->enlace == null) {
        $this->conectar();
    }

    $sql = "INSERT INTO medicamentos (nombreComercial, nombreGenerico, fecha_caducidad, requiere_receta, precio, stock, imagen) VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $this->enlace->prepare($sql);
    if (!$stmt) {
        die("Error en prepare (insertarMedicamento): " . $this->enlace->error);
    }

  
    $stmt->bind_param("sssidis", $nombreComercial, $nombreGenerico, $fecha_caducidad, $requiere_receta, $precio, $stock, $imagen);

    return $stmt->execute();
}

public function consultarMedicamentos($condicion = null) {
    if ($this->enlace === null) {
        $this->conectar();
    }

    $condicion = $condicion != null ? " WHERE " . $condicion : "";
    $sql = "SELECT * FROM medicamentos" . $condicion;
    $resultado = mysqli_query($this->enlace, $sql);
    return $resultado;
}

public function eliminarMedicamento($id) {
    if ($this->enlace == null) {
        $this->conectar();  // Asegúrate que conectar() crea $this->enlace como mysqli
    }

    // Preparar consulta para obtener imagen
    $sqlImagen = "SELECT imagen FROM medicamentos WHERE id_medicamento = ?";
    $stmtImagen = $this->enlace->prepare($sqlImagen);

    if (!$stmtImagen) {
        throw new Exception("Error en prepare para SELECT imagen: " . $this->enlace->error);
    }

    $stmtImagen->bind_param("i", $id);
    $stmtImagen->execute();
    $resultado = $stmtImagen->get_result();
    $fila = $resultado->fetch_assoc();

    if ($fila && !empty($fila['imagen']) && file_exists($fila['imagen'])) {
        unlink($fila['imagen']);
    }

    // Preparar consulta para eliminar producto
    $sqlEliminar = "DELETE FROM medicamentos WHERE id_medicamento = ?";
    $stmtEliminar = $this->enlace->prepare($sqlEliminar);

    if (!$stmtEliminar) {
        throw new Exception("Error en prepare para DELETE: " . $this->enlace->error);
    }

    $stmtEliminar->bind_param("i", $id);
    return $stmtEliminar->execute();
} 


public function caducos($diasAviso = 30) {
    if ($this->enlace === null) {
        $this->conectar();
    }

    // Contar cuántos medicamentos tienen fecha_caducidad <= hoy + $diasAviso días
    $sql = "
        SELECT COUNT(*) AS total
        FROM medicamentos
        WHERE fecha_caducidad <= DATE_ADD(CURDATE(), INTERVAL ? DAY)
    ";
    $stmt = $this->enlace->prepare($sql);
    if (!$stmt) {
        die("Error en prepare (caducos): " . $this->enlace->error);
    }

    $stmt->bind_param("i", $diasAviso);
    $stmt->execute();
    $result = $stmt->get_result();
    $fila = $result->fetch_assoc();
    return intval($fila['total']);
}


public function actualizarMedicamento(
    $id_medicamento,
    $nombreComercial,
    $nombreGenerico,
    $fecha_caducidad,
    $requiere_receta,
    $precio,
    $stock,
    $imagen
) {
    if ($this->enlace === null) {
        $this->conectar();
    }

    $sql = "
        UPDATE medicamentos
        SET nombreComercial  = ?,
            nombreGenerico   = ?,
            fecha_caducidad  = ?,
            requiere_receta  = ?,
            precio           = ?,
            stock            = ?,
            imagen           = ?
        WHERE id_medicamento  = ?
    ";

    $stmt = $this->enlace->prepare($sql);
    if (!$stmt) {
        die("Error en prepare (actualizarMedicamento): " . $this->enlace->error);
    }

   
    $stmt->bind_param(
        "sssidisi",
        $nombreComercial,   
        $nombreGenerico,    
        $fecha_caducidad,   
        $requiere_receta,   
        $precio,           
        $stock,             
        $imagen,            
        $id_medicamento     
    );

    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}


}
?>
