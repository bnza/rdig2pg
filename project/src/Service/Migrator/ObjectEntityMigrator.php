<?php

namespace App\Service\Migrator;

class ObjectEntityMigrator extends AbstractMigrator
{
    public function getInsertQuerySQL(): string
    {
        $query = <<<EOF
INSERT INTO %s 
(id, campaign, class, material_class, material_type, technique, type, color, preservation, decoration, no, duplicate, length, height, width, thickness, diameter, perforation_diameter, weight, retrieval_date, inscription, description, conservation_year, fragments, coord_n, coord_e, coord_z, location, drawing, photo, envanterlik, etutluk, sub_type)
VALUES 
(:id, :campaign, :class, :material_class, :material_type, :technique, :type, :color, :preservation, :decoration, :no, :duplicate, :length, :height, :width, :thickness, :diameter, :perforation_diameter, :weight, :retrieval_date, :inscription, :description, :conservation_year, :fragments, :coord_n, :coord_e, :coord_z, :location, :drawing, :photo, :envanterlik, :etutluk, :sub_type)
EOF;
        return sprintf($query, $this->getTable());
    }
}
