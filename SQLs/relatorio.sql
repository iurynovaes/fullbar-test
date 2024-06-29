WITH TotalPontos AS (
    SELECT 
        u.usuario_cpf,
        SUM(p.pontos_qtd) AS total_pontos
    FROM 
        usuario u
    LEFT JOIN 
        pontos p ON p.usuario_id = u.usuario_id
    GROUP BY 
        u.usuario_cpf
)
SELECT 
    u.usuario_nome AS 'Nome', 
    u.usuario_cpf AS 'CPF', 
    c.cargo_nome AS 'Cargo',
    COALESCE(tp.total_pontos, 0) AS 'Total de Pontos',
    CASE
        WHEN COALESCE(tp.total_pontos, 0) < 50 THEN 'Bronze'
        WHEN COALESCE(tp.total_pontos, 0) < 100 THEN 'Silver'
        ELSE 'Gold'
    END AS 'Classificação'
FROM 
    usuario u
INNER JOIN  
    cargo c ON c.cargo_id = u.cargo_id
LEFT JOIN 
    TotalPontos tp ON tp.usuario_cpf = u.usuario_cpf;
