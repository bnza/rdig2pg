<?php

namespace App\Service\Migrator;

class PotteryEntityMigrator extends AbstractMigrator
{
    public function getInsertQuerySQL(): string
    {
        $query = <<<EOF
INSERT INTO %s 
(id, class, core_color, firing, inclusions_frequency, inclusions_size, inclusions_type_id, inner_color, inner_decoration, inner_surface_treatment, outer_color, outer_surface_treatment, preservation, shape, technique, base_diameter, base_height, base_width, drawing_number, height, location, max_wall_diameter, restored, rim_diameter, rim_width, wall_width, envanterlik, etutluk) 
VALUES 
(:id, :class, :core_color, :firing, :inclusions_frequency, :inclusions_size, :inclusions_type_id, :inner_color, :inner_decoration, :inner_surface_treatment, :outer_color, :outer_surface_treatment, :preservation, :shape, :technique, :base_diameter, :base_height, :base_width, :drawing_number, :height, :location, :max_wall_diameter, :restored, :rim_diameter, :rim_width, :wall_width, :envanterlik, :etutluk);
EOF;

        return sprintf($query, $this->getTable());
    }
}
