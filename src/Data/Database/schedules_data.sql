INSERT INTO
    ecf_garage.schedules (
        day, morning_schedule, afternoon_schedule
    )
VALUES (
        'Lundi', '9h – 13h', '14h – 19h'
    ),
    (
        'Mardi', '9h – 13h', '14h – 19h'
    ),
    ('Mercredi', '9h – 19h', NULL),
    (
        'Jeudi', '9h – 13h', '14h – 19h'
    ),
    (
        'Vendredi', '9h – 13h', '14h – 19h'
    ),
    ('Samedi', '9h – 19h', NULL),
    ('Dimanche', 'FERME', NULL),
    ('Ferie', 'FERME', NULL);