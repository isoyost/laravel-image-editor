Podczas modyfikacji i tworzenia redis jest aktualizowany o zadane dane statystyczne.
### GET: /api/images
Lista wszystkich zdjęć.
### POST: /api/images
Utworzenie nowego zdjęcia.
- `file` - plik ze zdjęciem
### GET: /api/images/{id}
Podane zdjęcie.
### PATCH: /api/images/{id}
Modyfikacja zdjęcia.
- `resize_to` - wymiary docelowe
- `convert_to` - format docelowy

