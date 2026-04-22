with rooms as (
select *  FROM habitaciones
),floor as (
select * from pisos
),room_types as (
select * from habitaciones_tipos
),total_guests as(

SELECT 
    r.id, 
    (COUNT(r.id) + IFNULL(a.acom, 0)) AS Total_huespedes
FROM registros r
LEFT JOIN (
    SELECT registro_id, COUNT(*) AS acom 
    FROM registro_acompanantes 
    GROUP BY registro_id
) a ON r.id = a.registro_id
GROUP BY r.id

),reservations as (
select r.*,
       te.codigo Cod_Estadia,
       te.nombre Nom_estadia,
       tg.Total_huespedes,
       fp.codigo Cod_Forma_pago,
       fp.descripcion Forma_pago,
       concat(h.nombre,' ' ,h.apellido) Nombre_Huesped
       from registros r
LEFT JOIN tipo_estadia te ON r.tipo_estadia_id=te.id
LEFT JOIN total_guests tg ON r.id=tg.id
LEFT JOIN formas_pago fp  ON r.forma_pago_id =fp.id
LEFT JOIN huespedes h     ON r.huesped_id =h.id
WHERE estado_servicio='ACTIVO'
)

select  h.id,
        h.numero,
        f.piso piso,
        f.nombre Piso_Descripcion,
        rt.clave cod_tip_habitacion,
        rt.nombre tip_habitacion,
        re.Cod_Estadia,
		re.Nom_estadia,
        re.Total_huespedes,
        re.Cod_Forma_pago,
		re.Forma_pago,
        re.precio_base,
        re.hora_entrada,
		re.hora_salida,
        re.Nombre_Huesped
        from rooms h
LEFT JOIN  floor f ON f.id=h.piso_id       
LEFT JOIN  room_types rt ON f.id=h.piso_id
LEFT JOIN reservations re on re.habitacion_id =h.id;




  
