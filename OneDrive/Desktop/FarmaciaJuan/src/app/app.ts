import { Component, inject } from '@angular/core';
import { AsyncPipe } from '@angular/common';
import { Firestore } from '@angular/fire/firestore';

@Component({
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.css'],
  imports: [AsyncPipe],
})
export class AppComponent {
  firestore: Firestore = inject(Firestore);

  constructor() {

  }
}