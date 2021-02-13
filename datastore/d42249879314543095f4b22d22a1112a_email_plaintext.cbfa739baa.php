<?php

return <<<'VALUE'
"namespace IPS\\Theme;\n\tfunction email_html_core_unsubscribeNotNeeded( $member, $email ) {\n\t\t$return = '';\n\t\t$return .= <<<CONTENT\n\n<tr>\n\t<td dir='{dir}' valign=\"top\"><img src='\nCONTENT;\n\n$return .= \\IPS\\Settings::i()->base_url;\n$return .= <<<CONTENT\napplications\/core\/interface\/email\/spacer.png' width='1' height='1' alt=''><\/td>\n\t<td dir='{dir}' valign='middle' align='center' style=\"font-family: 'Helvetica Neue', helvetica, sans-serif; font-size: 12px; line-height: 18px; padding-left: 10px;\">\n\t\t{$email->language->addToStack(\"unsubscribe_not_needed\", FALSE)}\n\t\t<br \/>\n\t<\/td>\n<\/tr>\nCONTENT;\n\n\t\treturn $return;\n}\n\tfunction email_plaintext_core_unsubscribeNotNeeded( $member, $email ) {\n\t\t$return = '';\n\t\t$return .= <<<CONTENT\n\n{$email->language->addToStack(\"unsubscribe_not_needed\", FALSE)}\nCONTENT;\n\n\t\treturn $return;\n}"
VALUE;
