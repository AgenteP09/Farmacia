import { Component, inject } from '@angular/core';
import { Firestore, collection, addDoc } from '@angular/fire/firestore';

@Component({
  selector: 'app-root',
  standalone: true,
  templateUrl: './app.component.html',
  styleUrls: ['./app.css'],
  imports: []
})
export class AppComponent {
  // Inyectamos la base de datos de Firebase
  private firestore: Firestore = inject(Firestore);

  async enviarDatos(event: Event) {
    event.preventDefault();
    const target = event.target as any;
    
    // Obtenemos los valores del formulario
    const nuevoProducto = {
      nombre: target.nombre.value,
      precio: target.precio.value,
      fecha: new Date()
    };

    try {
      // Guardamos en una colección llamada 'productos'
      const colRef = collection(this.firestore, 'productos');
      await addDoc(colRef, nuevoProducto);
      
      alert("¡Producto guardado en la nube!");
      target.reset();
    } catch (e) {
      console.error("Error al guardar: ", e);
    }
  }
}