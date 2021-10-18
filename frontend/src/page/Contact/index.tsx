import styled from "styled-components";
import {MOBILE_MAX_WIDTH, PageTitle} from "../../Constants";

import * as colour from '../../css/colour'
import * as text from '../../css/text'
import * as padding from '../../css/padding'

const StaticContactInfo = styled.h2`
  ${text.DEFAULT};
  ${text.CENTERED}
  margin: 0 auto;
  font-size: 20px;
  
  ${MOBILE_MAX_WIDTH} {
    font-size: 15px;
  }
`;

const ContactContainer = styled.div`
  ${colour.SECTION_1}
  ${padding.ROUNDED}
  ${padding.DEFAULT}
  
  margin: 0 auto;
  height: max-content;
  width: max-content;
`;

function Contact() {
    return (
        <ContactContainer>
            <PageTitle>Contact Us</PageTitle>
            <StaticContactInfo>Email: admin@test.com</StaticContactInfo>
            <br />
            <br />
            <br />
            <StaticContactInfo>Phone: 00000 000 000</StaticContactInfo>
        </ContactContainer>
    )
}

export default Contact